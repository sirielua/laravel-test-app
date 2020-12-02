<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\Participant\RegistrationService;

class RegistrationDataVars extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $service = app()->make(RegistrationService::class);
        $data = $service->getData();

        echo '<pre>';
        print_r([
            'stage' => $data->getStage(),
            'id' => $data->getId(),
            'contest_id' => $data->getContestId(),
            'first_name' => $data->getFirstName(),
            'last_name' => $data->getLastName(),
            'phone' => $data->getPhone(),
            'referral_id' => $data->getReferralId(),
        ]);
        echo '</pre>';
    }
}
