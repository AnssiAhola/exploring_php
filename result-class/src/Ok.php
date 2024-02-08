<?php

class Ok implements ResponseInterface
{
    public function __construct(private string $value)
    {
    }

    public function getStatusCode(): int
    {
        return 200;
    }

    public function getMessage(): string
    {
        return $this->value;
    }
}
