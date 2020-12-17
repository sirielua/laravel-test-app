<?php

namespace Modules\Admin\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

use App\Models\Participant;
use App\Components\Sms\SmsApi;

class DashboardQuikStats extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('admin::_components.dashboard-quik-stats', [
            'participantsToday' => $this->getParticipantsToday(),
            'participantsTotal' => $this->getParticipantsTotal(),
            'smsBalance' => $this->getSmsBalance(),
        ]);
    }

    private function getParticipantsToday()
    {
        $date = (new \DateTimeImmutable())
            ->setTimestamp(time() - 24*3600)
            ->format('Y-m-d H:i:s');

        return Participant::confirmed()
            ->where('registration_confirmed_at', '>', $date)
            ->count();
    }

    private function getParticipantsTotal()
    {
        return Participant::confirmed()->count();
    }

    private function getSmsBalance()
    {
        return Cache::remember('sms-balance:sms-left', 60*5, function () {
            $api = app()->make(SmsApi::class);
            $balance = $api->getBalance();
            return $balance->getSmsCount();
        });
    }
}
