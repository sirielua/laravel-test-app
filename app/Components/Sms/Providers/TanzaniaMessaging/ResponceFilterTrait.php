<?php

namespace App\Components\Sms\Providers\TanzaniaMessaging;

use App\Components\Sms\Exceptions\ResponseException;
use App\Components\Sms\Exceptions\NotAuthorizedException;
use App\Components\Sms\Exceptions\NoResponseException;

use GuzzleHttp\Exception\ClientException;

trait ResponceFilterTrait
{
    protected function formatException(ClientException $e)
    {
        $code = $e->getCode();
        $message = $e->getMessage();

        if (($e->getCode() >= 401) || ($e->getCode() < 403)) {
            return new NotAuthorizedException($message, $code);
        } else {
            return new ResponseException($message, $code);
        }
    }
}

