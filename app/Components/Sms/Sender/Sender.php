<?php

namespace App\Components\Sms\Sender;

interface Sender
{
    /**
     *
     * @param type $from
     * @param type $to
     * @param type $message
     * @return \App\Components\Sms\Sender\Result
     */
    public function send($to, $message): Result;
}
