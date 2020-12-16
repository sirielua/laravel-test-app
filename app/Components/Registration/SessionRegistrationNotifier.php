<?php

namespace App\Components\Registration;

use App\domain\components\RegistrationNotifier\RegistrationNotifier;
use Illuminate\Http\Request;
use App\domain\entities\Participant\Participant;

class SessionRegistrationNotifier implements RegistrationNotifier
{
    private $messageGen;
    private $request;

    public function __construct(RegistrationConfirmationMessageGenerator $messageGen, Request $request)
    {
        $this->messageGen = $messageGen;
        $this->request = $request;
    }

    /**
     * @throws exceptions\FailedToNotifyException
     */
    public function notify(Participant $participant): void
    {
        $this->request->session()->flash('status', $this->messageGen->generate($participant));
    }
}
