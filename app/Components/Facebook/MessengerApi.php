<?php

namespace App\Components\Facebook;

interface MessengerApi
{
    public function sendMesage($psid, $message);
}
