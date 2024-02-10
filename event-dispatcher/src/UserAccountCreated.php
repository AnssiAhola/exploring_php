<?php

use Symfony\Contracts\EventDispatcher\Event;

class UserAccountCreated extends Event
{
    public function __construct(private string $username)
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
