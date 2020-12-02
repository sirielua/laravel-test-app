<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

use App\Services\Participant\RegistrationService;
use App\Services\Participant\RegistrationData;

class CheckParticipantRegistrationState
{
    private $service;

    public function __construct(RegistrationService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->checkIsRouteAllowed($request->route())) {
            return redirect($this->getRedirectRoute());
        }

        return $next($request);
    }

    private function checkIsRouteAllowed(Route $route)
    {
        $allowedStagesPerRoute = $this->getAllowedStagesPerRoute();
        $allowedStages = $allowedStagesPerRoute[$route->getName()] ?? [];
        $currentStage = $this->service->data->getStage();

        return $allowedStages ? in_array($currentStage, $allowedStages) : false;
    }

    private function getAllowedStagesPerRoute()
    {
        return [
            'index' => [RegistrationData::STAGE_REGISTER, RegistrationData::STAGE_VERIFICATION],
            'participants.register' => [RegistrationData::STAGE_REGISTER, RegistrationData::STAGE_VERIFICATION],
            'participants.verify' => [RegistrationData::STAGE_VERIFICATION],
            'participants.resend-verification' => [RegistrationData::STAGE_VERIFICATION],
            'participants.edit-number' => [RegistrationData::STAGE_VERIFICATION],
            'participants.confirm' => [RegistrationData::STAGE_VERIFICATION],
            'participants.share' => [RegistrationData::STAGE_SHARE, RegistrationData::STAGE_MESSENGER],
            'participants.messenger' => [RegistrationData::STAGE_SHARE, RegistrationData::STAGE_MESSENGER],
        ];
    }

    private function getRedirectRoute()
    {
        $routeNames = $this->getDefaultRoutePerStage();
        $currentStage = $this->service->data->getStage();

        return route($routeNames[$currentStage]);
    }

    private function getDefaultRoutePerStage()
    {
        return [
            RegistrationData::STAGE_REGISTER => 'index',
            RegistrationData::STAGE_VERIFICATION => 'participants.verify',
            RegistrationData::STAGE_SHARE => 'participants.share',
            RegistrationData::STAGE_MESSENGER => 'participants.messenger',
        ];
    }
}
