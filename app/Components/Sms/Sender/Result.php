<?php

namespace App\Components\Sms\Sender;

class Result
{
    private $result;
    private $id;
    private $message;

    public function __construct(bool $result, $id = null, $message = null)
    {
        $this->result = $result;
        $this->id = $id;
        $this->message = $message;
    }

    public function isSuccess()
    {
        return $this->result === true;
    }

    public function isError()
    {
        return $this->result === false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
