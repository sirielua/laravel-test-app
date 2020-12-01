<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
//use Illuminate\Http\Response;

use App\Services\Participant\RegistrationService;
use App\Http\Requests\RegisterParticipantRequest;

class ParticipantController extends Controller
{
    /**
     * Show the contest template
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(RegistrationService $service)
    {
        return view('participants.index', [
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
    public function resendVerification()
    {

    }

    /**
     * Edit number
     */
    public function editNumber()
    {

    }

    /**
     * Confirm verification
     */
    public function confirm()
    {

    }

    /**
     * Show page with share buttons and participant referral link
     */
    public function share()
    {

    }

    /**
     * Offer to connect a messenger
     */
    public function messenger()
    {

    }

    /**
     * Public user stats page
     */
    public function user()
    {

    }
}
