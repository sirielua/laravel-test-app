<?php

namespace App\domain\service;

abstract class CommandHandler
{
    abstract public function handle($command): void;
}