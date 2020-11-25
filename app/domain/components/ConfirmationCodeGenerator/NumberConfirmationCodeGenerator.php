<?php

namespace App\domain\components\ConfirmationCodeGenerator;

use Assert\Assertion;

class NumberConfirmationCodeGenerator implements ConfirmationCodeGenerator
{
    private $length;

    public function __construct($length = 4)
    {
        Assertion::integer($length);
        Assertion::greaterThan($length, 0);

        $this->length = $length;
    }

    public function generate(): string
    {
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= \rand(0, 9);
        }
        return $code;
    }
}
