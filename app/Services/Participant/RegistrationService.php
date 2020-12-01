<?php

namespace App\Services\Participant;

use App\Models\Contest;
use App\Models\Participant;

use App\domain\service\Participant\Register\RegisterCommand;
use App\domain\service\Participant\Register\RegisterHandler;

class RegistrationService
{
    private $data;

    public function __construct(RegistrationData $data)
    {
        $this->data = $data;

        if (!$this->data->getStage()) {
            $this->data->setStage(RegistrationData::STAGE_REGISTER);
        }
    }

    public function __get($name)
    {
        $method = 'get'.ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new \ErrorException ('Undefined property: ' . get_class($this) . '::$' . $name);
    }

    public function getData(): RegistrationData
    {
        return $this->data;
    }

    public function getContest()
    {
        $contest = null;

        if ($this->data->getContestId()) {
            $contest = Contest::active()->find($this->data->getContestId());
        }

        if (!$contest) {
            $contest = Contest::active()->firstOrFail();
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
    }
}
