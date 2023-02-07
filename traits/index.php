<?php
require_once('./vendor/autoload.php');

trait GreetingTrait
{
    public function greet(): string
    {
        return "Hello from " . static::class . "!";
    }
}

class Foo
{
    use GreetingTrait;
}

class Bar
{
    use GreetingTrait;
}

class Baz
{
    use GreetingTrait;
}

/** @var GreetingTrait[] $subjects */
$subjects = [
    new Foo,
    new Bar,
    new Baz,
];

foreach ($subjects as $subject) {
    echo $subject->greet() . PHP_EOL;
}
