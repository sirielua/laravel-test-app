<?php

namespace App\repositories;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\repositories\NotFoundException;
use App\domain\repositories\DuplicateKeyException;

use App\Models\Participant as Model;
use App\domain\repositories\Hydrator;

use App\domain\entities\Participant\Participant;
use App\domain\entities\Participant\Id;
use App\domain\entities\Contest\Id as ContestId;
use App\domain\entities\Participant\Name;
use App\domain\entities\Participant\Phone;
use App\domain\entities\Participant\RegistrationData;
use App\domain\entities\Participant\RegistrationStatus;
use App\domain\entities\Participant\FacebookId;

class EloquentParticipantRepository implements ParticipantRepository
{
    private $hydrator;

    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function get(Id $id): Participant
    {
        if (!$model = Model::find((string)$id)) {
            throw new NotFoundException('Participant not found.');
        }

        $participant = $this->hydrator->hydrate(Participant::class, [
            'id' => new Id($model->id),
            'contestId' => new ContestId($model->contest_template_id),
            'name' => new Name($model->first_name, $model->last_name),
            'phone' => new Phone($model->phone),
            'registrationData' => new RegistrationData(
                new RegistrationStatus($model->registration_status),
                new \DateTimeImmutable($model->registered_at),
                $model->registration_confirmation_code,
                $model->registration_confirmations_received,
                $model->registration_confirmations_attempts,

                $model->registration_confirmation_received_at ? new \DateTimeImmutable($model->registration_confirmation_received_at) : null,
                $model->registration_confirmation_last_attempt_at ? new \DateTimeImmutable($model->registration_confirmation_last_attempt_at) : null,
                $model->registration_confirmed_at ? new \DateTimeImmutable($model->registration_confirmed_at) : null,
            ),
            'referralId' => $model->referral_id ? new Id($model->referral_id) : null,
            'referralQuantity' => $model->referral_quantity,
            'facebookId' => $model->facebook_id ? new FacebookId($model->facebook_id) : null,
        ]);

        return $participant;
    }

    public function add(Participant $participant): void
    {
        if ($model = Model::find((string)$participant->getId())) {
            throw new DuplicateKeyException('Participant already exists');
        }

        $model = new Model();
        $this->persist($participant, $model);
    }

    private function persist(Participant $participant, Model $model)
    {
        if (!$model->exists) {
            $model->id = (string)$participant->getId();
        }

        $model->first_name = $participant->getName()->getFirstName();
        $model->last_name = $participant->getName()->getLastName();
        $model->phone = (string)$participant->getPhone();
        $model->contest_template_id = (string)$participant->getContestId();
        $model->referral_id = $participant->getReferralId() ? (string)$participant->getReferralId() : null;
        $model->referral_quantity = $participant->getReferralQuantity();
        $model->facebook_id = $participant->getFacebookId() ? (string)$participant->getFacebookId() : null;

        $registrationData = $participant->getRegistrationData();

        $model->registration_status = $registrationData->getStatus()->getStatus();
        $model->registration_confirmation_code = $registrationData->getConfirmationCode();
        $model->registration_confirmations_received = $registrationData->getConfirmationReceivedTimes();
        $model->registration_confirmations_attempts = $registrationData->getConfirmationAttempts();
        $model->registered_at = $registrationData->getRegisteredAt()->format('Y-m-d H:i:s');

        $confirmationReceivedAt = $registrationData->getConfirmationReceivedAt();
        $lastConfirmationAttemptAt = $registrationData->getLastConfirmationAttemptAt();
        $confirmedAt = $registrationData->getConfirmedAt();

        $model->registration_confirmation_received_at = $confirmationReceivedAt ? $confirmationReceivedAt->format('Y-m-d H:i:s') : null;
        $model->registration_confirmation_last_attempt_at = $lastConfirmationAttemptAt ? $lastConfirmationAttemptAt->format('Y-m-d H:i:s') : null;
        $model->registration_confirmed_at = $confirmedAt ? $confirmedAt->format('Y-m-d H:i:s') : null;

        $model->save();
    }

    public function save(Participant $participant): void
    {
        if (!$model = Model::find((string)$participant->getId())) {
            throw new NotFoundException('Participant not found.');
        }

        $this->persist($participant, $model);
    }

    public function remove(Participant $participant): void
    {
        if (!$model = Model::find((string)$participant->getId())) {
            throw new NotFoundException('Participant not found.');
        }

        $model->delete();
    }

    public function existsByPhone(Phone $phone): bool
    {
        return Model::where('phone', (string)$phone)->exists();
    }

    public function getReferralQuantity(Id $id): int
    {
        return Model::where([
            ['referral_id', (string)$id],
            ['registration_status', Model::STATUS_CONFIRMED]
        ])->count();
    }
}
