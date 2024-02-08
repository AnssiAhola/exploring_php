<?php

interface ResponseInterface
{
    public function getStatusCode(): int;
    public function getMessage(): string;
}
