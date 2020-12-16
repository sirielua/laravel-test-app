<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Http\Response;

use App\Services\Participant\RegistrationService;
use App\Http\Requests\RegisterParticipantRequest;
use App\Http\Requests\ConfirmParticipantRegistrationRequest;

use App\domain\service\Participant\Register\exceptions\PhoneAlreadyRegisteredException;
use App\domain\service\Participant\SendConfirmation\exceptions\CantSendMoreConfirmationsException;
use App\domain\service\Participant\ConfirmRegistration\exceptions\InvalidConfirmationCodeException;
use Illuminate\Validation\ValidationException;

use App\Models\Participant;
use App\Models\Contest;

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
     * Reset all registration data and start over
     *
     * @param RegistrationService $service
     * @return type
     */
    public function registerAgain(RegistrationService $service)
    {
        $service->resetState();

        return redirect(route('index'));
    }

    /**
     * Public user stats page
     */
    public function referral($id, RegistrationService $service)
    {
        $service->referral($id);

        return redirect(route('index'));
    }

    /**
     * Register user
     */
    public function register(RegisterParticipantRequest $request, RegistrationService $service)
    {
        try {
            $service->register($request->validated());
        } catch (PhoneAlreadyRegisteredException $e) {
            return redirect(route('index'));
        }

        return redirect(route('participants.verify'));
    }

    /**
     * Show verification form
     */
    public function verify(RegistrationService $service)
    {
        $view = 'participants.verify-link'; // 'participants.verify-code' | 'participants.verify-link'

        return view($view, [
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
        $service->editNumber();

        return redirect(route('index'));
    }

    /**
     * Confirm verification
     */
    public function confirmWithCode(ConfirmParticipantRegistrationRequest $request, RegistrationService $service)
    {
        try {
            $service->confirmWithCode($request->get('code'));
        } catch (InvalidConfirmationCodeException $e) {
            throw ValidationException::withMessages([
                'code' => ['Invalid confirmation code'],
            ]);
        }

        return redirect(route('participants.share'));
    }

    /**
     * Confirm verification
     */
    public function confirmWithLink($id, $code, RegistrationService $service)
    {
        try {
            $service->confirmWithLink($id, $code);
        } catch (\Exception $e) {
            abort(403, 'Invalid confirmation code');
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
        return view('participants.messenger', [
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
        $contest = Contest::findOrFail($participant->contest_template_id);

        return view('participants.user', [
            'participant' => $participant,
            'contest' => $contest,
        ]);
    }
}
