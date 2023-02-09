<?php

class MyClass
{


    public function __construct(
        private int $foo = 0,
        private string $bar = "",
        private bool $baz = false,
        private float $foofloat = 0.0,
    ) {
    }

    public function getFoo(): int
    {
        return $this->foo;
    }

    public function getBar(): string
    {
        return $this->bar;
    }

    public function getBaz(): bool
    {
        return $this->baz;
    }

    public function getFooFloat(): float
    {
        return $this->foofloat;
    }
}
