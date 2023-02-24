<?php

// This docblock enables correct typehints for FooCollection items
/** @extends Collection<Foo> */
class FooCollection extends Collection
{
    public function hasValidFoos(): bool
    {
        return !$this->getValidFoos()->isEmpty();
    }

    public function getValidFoos(): self
    {
        return $this->findWhere(fn (Foo $foo) => $foo->isValid());
    }
}
