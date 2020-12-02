<?php

namespace App\Components;

use App\domain\components\RegistrationNotifier\RegistrationNotifier;
use Illuminate\Http\Request;
use App\domain\entities\Participant\Participant;

class SessionRegistrationNotifier implements RegistrationNotifier
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * @throws exceptions\FailedToNotifyException
     */
    public function notify(Participant $participant): void
    {
        $code = $participant->getRegistrationData()->getConfirmationCode();

        $this->request->session()->flash('status', 'Your confirmation code is: '. $code);
    }
}
