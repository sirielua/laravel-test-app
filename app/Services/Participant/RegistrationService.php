<?php

namespace App\Services\Participant;

use App\Models\Contest;
use App\Models\Participant;

use App\domain\service\Participant\Register\RegisterCommand;
use App\domain\service\Participant\Register\RegisterHandler;

use App\domain\service\Participant\SendConfirmation\SendConfirmationCommand;
use App\domain\service\Participant\SendConfirmation\SendConfirmationHandler;
use App\domain\service\Participant\SendConfirmation\exceptions\OnlyUnconfirmedParticipantsCouldBeNotifiedException;

use App\domain\service\Participant\Remove\RemoveCommand;
use App\domain\service\Participant\Remove\RemoveHandler;

use App\domain\service\Participant\ConfirmRegistration\ConfirmRegistrationCommand;
use App\domain\service\Participant\ConfirmRegistration\ConfirmRegistrationHandler;
use App\domain\service\Participant\ConfirmRegistration\exceptions\RegistrationAlreadyConfirmedException;
use App\domain\service\Participant\ConfirmRegistration\exceptions\InvalidConfirmationCodeException;

use App\domain\repositories\NotFoundException;

class RegistrationService
{
    private $data;
    private $contest;
    private $participant;

    public function __get($name)
    {
        $method = 'get'.ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new \ErrorException ('Undefined property: ' . get_class($this) . '::$' . $name);
    }

    public function __construct(RegistrationData $data)
    {
        $this->data = $data;
        $this->init();
    }

    private function init()
    {
        if (!$this->isStateValid()) {
            $this->resetState();
        }
    }

    private function isStateValid()
    {
        if (!$this->data->getStage()) {
            return false;
        }

        if ($this->data->getContestId() && !$this->getContest()) {
            return false;
        }

        if ($this->data->getId() && !$this->getParticipant()) {
            return false;
        }

        return true;
    }

    public function resetState()
    {
        $this->data->setStage(RegistrationData::STAGE_REGISTER);
        $this->data->setId(null);
        $this->data->setContestId(null);
        $this->data->setFirstName(null);
        $this->data->setLastName(null);
        $this->data->setPhone(null);
        $this->data->setReferralId(null);

        $this->participant = $this->contest = null;
    }

    public function getContest()
    {
        $contest = null;

        if ($this->data->getContestId()) {
            $contest = Contest::active()->find($this->data->getContestId());
        }

        if (!$contest) {
            $contest = Contest::active()->inRandomOrder()->firstOrFail();
            $this->data->setContestId($contest->id);
        }

        return $contest;
    }

    public function getParticipant()
    {
        if ($this->data->getId()) {
            return Participant::find($this->data->getId());
        }
    }

    public function getData(): RegistrationData
    {
        return $this->data;
    }

    public function referral($id)
    {
        $contestId = $this->data->getContestId();

        $this->resetState();
        $this->data->setReferralId($id);
        $this->data->setContestId($contestId);
        $this->data->setStage(RegistrationData::STAGE_REGISTER);
    }

    public function register($data)
    {
        $this->data->setFirstName($data['first_name']);
        $this->data->setLastName($data['last_name']);
        $this->data->setPhone($data['phone']);
        $this->data->setReferralId($data['referral_id']);

        $command = new RegisterCommand(
            $this->data->getContestId(),
            $this->data->getFirstName(),
            $this->data->getLastName(),
            $this->data->getPhone(),
            $this->data->getReferralId(),
        );
        $handler = app()->make(RegisterHandler::class);

        $id = $handler->handle($command);

        $this->data->setId((string)$id);
        $this->data->setStage(RegistrationData::STAGE_VERIFICATION);

        $this->sendVerification();
    }

    public function sendVerification()
    {
        $command = new SendConfirmationCommand($this->data->getId());
        $handler = app()->make(SendConfirmationHandler::class);

        try {
            $handler->handle($command);
        } catch (OnlyUnconfirmedParticipantsCouldBeNotifiedException $e) {
            $this->data->setStage(RegistrationData::STAGE_SHARE);
        }
    }

    public function editNumber()
    {
        $command = new RemoveCommand($this->data->getId());
        $handler = app()->make(RemoveHandler::class);

        $handler->handle($command);

        $this->data->setStage(RegistrationData::STAGE_REGISTER);
        $this->data->setId(null);
        $this->participant = null;
    }

    public function confirmWithCode($code)
    {
        $command = new ConfirmRegistrationCommand($this->data->getId(), $code);
        $handler = app()->make(ConfirmRegistrationHandler::class);

        try {
            $handler->handle($command);
            $this->data->setStage(RegistrationData::STAGE_SHARE);
        } catch (RegistrationAlreadyConfirmedException $e) {
            $this->data->setStage(RegistrationData::STAGE_SHARE);
        }
    }

    /**
     *
     * @param string $id
     * @param string $code
     * @throws NotFoundException
     * @throws RegistrationAlreadyConfirmedException
     * @throws InvalidConfirmationCodeException
     */
    public function confirmWithLink($id, $code)
    {
        $command = new ConfirmRegistrationCommand($id, $code);
        $handler = app()->make(ConfirmRegistrationHandler::class);

        $handler->handle($command);

        $this->regenerateState($id);
    }

    /**
     *
     * @param type $id
     * @throws NotFoundException
     */
    private function regenerateState($id)
    {
        $this->resetState();

        try {
            $participant = Participant::find($id);
            $stage = $participant->isConfirmed() ?
                RegistrationData::STAGE_SHARE : RegistrationData::STAGE_VERIFICATION;

            $this->data->setStage($stage);
            $this->data->setId($participant->id);
            $this->data->setContestId($participant->contest_id);
            $this->data->setFirstName($participant->first_name);
            $this->data->setLastName($participant->last_name);
            $this->data->setPhone($participant->phone);
            $this->data->setReferralId($participant->referral_id);
        } catch (NotFoundException $e) {
            $this->data->setStage(RegistrationData::STAGE_REGISTER);
        }
    }
}
