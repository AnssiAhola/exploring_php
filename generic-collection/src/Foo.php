<?php

class Foo implements Stringable
{

    public function __construct(
        private ?string $baz = null
    ) {
    }

    public function isValid(): bool
    {
        return !is_null($this->baz);
    }

    public function __toString(): string
    {
        return $this->baz ?? "N/A";
    }
}
