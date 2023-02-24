<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FooCollectionTest extends TestCase
{

    public function testHasValidFoos(): void
    {
        $collection = new FooCollection([new Foo, new Foo("This Is Valid"), new Foo]);
        $this->assertTrue($collection->hasValidFoos());
    }
}