<?php

use Symfony\Contracts\EventDispatcher\Event;

class UserAccountDeleted extends Event
{
    public function __construct(private int $userId)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
