<?php

namespace App\Services\Participant;

interface RegistrationData
{
    const STAGE_REGISTER = 'register';
    const STAGE_VERIFICATION = 'verification';
    const STAGE_SHARE = 'share';
    const STAGE_MESSENGER = 'messenger';

    public function setStage($value);
    public function getStage();

    public function setId($value);
    public function getId();

    public function setContestId($value);
    public function getContestId();

    public function setFirstName($value);
    public function getFirstName();

    public function setLastName($value);
    public function getLastName();

    public function setPhone($value);
    public function getPhone();

    public function setReferralId($value);
    public function getReferralId();
}

