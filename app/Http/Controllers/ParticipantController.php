<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Http\Response;

use App\Services\Participant\RegistrationService;
use App\Http\Requests\RegisterParticipantRequest;
use App\Http\Requests\ConfirmParticipantRegistrationRequest;

use App\domain\service\Participant\SendConfirmation\exceptions\CantSendMoreConfirmationsException;
use App\domain\service\Participant\ConfirmRegistration\exceptions\InvalidConfirmationCodeException;
use Illuminate\Validation\ValidationException;

use App\Models\Participant;

class ParticipantController extends Controller
{
    /**
     * Show the contest template
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function landing(RegistrationService $service)
    {
        return view('participants.landing', [
            'contest' => $service->getContest(),
            'data' => [
                'first_name' => $service->data->getFirstName(),
                'last_name' => $service->data->getLastName(),
                'phone' => $service->data->getPhone(),
                'referral_id' => $service->data->getReferralId(),
            ],
        ]);
    }

    /**
     * Register user
     */
    public function register(RegisterParticipantRequest $request, RegistrationService $service)
    {
        $service->register($request->validated());

        return redirect(route('participants.verify'));
    }

    /**
     * Show verification form
     */
    public function verify(RegistrationService $service)
    {
        return view('participants.verify', [
            'contest' => $service->getContest(),
            'participant' => $service->getParticipant(),
        ]);
    }

    /**
     * Re-notify about verification
     */
    public function resendVerification(Request $request, RegistrationService $service)
    {
        try {
            $service->sendVerification();
        } catch (CantSendMoreConfirmationsException $e) {
            $request->session()->flash('error', 'Cant send more confirmation sms!');
        }

        return redirect(route('participants.verify'));
    }

    /**
     * Edit number
     */
    public function editNumber(RegistrationService $service)
    {
        echo 'edit'; exit;

        $service->editNumber();

        return redirect(route('index'));
    }

    /**
     * Confirm verification
     */
    public function confirm(ConfirmParticipantRegistrationRequest $request, RegistrationService $service)
    {
        try {
            $service->confirm($request->get('code'));
        } catch (InvalidConfirmationCodeException $e) {
            throw ValidationException::withMessages([
                'code' => ['Invalid confirmation code'],
            ]);
        }

        return redirect(route('participants.share'));
    }

    /**
     * Show page with share buttons and participant referral link
     */
    public function share(RegistrationService $service)
    {
        return view('participants.share', [
            'contest' => $service->getContest(),
            'participant' => $service->getParticipant(),
        ]);
    }

    /**
     * Offer to connect a messenger
     */
    public function messenger(RegistrationService $service)
    {
        return view('participants.share', [
            'contest' => $service->getContest(),
            'participant' => $service->getParticipant(),
        ]);
    }

    /**
     * Public user stats page
     */
    public function user($id)
    {
        $participant = Participant::findOrFail($id);

        return view('participants.user', [
            'participant' => $participant,
        ]);
    }

    public function registerAgain(RegistrationService $service)
    {
        $service->resetState();

        return redirect(route('index'));
    }
}
