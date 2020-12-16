<?php

namespace App\domain\components\ConfirmationCodeGenerator;


class HashConfirmationCodeGenerator implements ConfirmationCodeGenerator
{
    public function generate(): string
    {
        return \md5(\uniqid());
    }
}
