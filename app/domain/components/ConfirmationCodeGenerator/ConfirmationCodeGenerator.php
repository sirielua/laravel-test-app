<?php

namespace App\domain\components\ConfirmationCodeGenerator;

interface ConfirmationCodeGenerator
{
    public function generate(): string;
}
