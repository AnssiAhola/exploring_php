<?php
require_once('./vendor/autoload.php');

$collection = new Collection([
    new Foo("Lorem"),
    new Foo("Ipsum"),
    new Foo("Dolor"),
    new Foo(),
    new Foo("Amet"),
]);

foreach ($collection as $foo) {
    if ($foo->isValid()) {
        echo $foo . PHP_EOL;
    }
}
// Output:
// Lorem
// Ipsum
// Dolor
// Amet

$collection = new FooCollection([
    new Foo("Lorem"),
    new Foo(),
    new Foo("Ipsum"),
]);

foreach ($collection->getValidFoos() as $foo) {
    echo $foo . PHP_EOL;
}
// Output:
// Lorem
// Ipsum
