<?php

class NotFound implements ResponseInterface
{
    public function getStatusCode(): int
    {
        return 404;
    }

    public function getMessage(): string
    {
        return "Not Found";
    }
}
