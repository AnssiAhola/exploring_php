<?php
require_once('./vendor/autoload.php');

// Populated by submitted form
$_POST["foo"] = "1";
$_POST["foofloat"] = "1.0";
$_POST["bar"] = "1";
$_POST["baz"] = "1";

// Configure and build the mapper
$builder = new MapperBuilder();
$mapper = $builder
    ->configureWith(MapperConfiguration::makeDefault())
    ->build();

// Map submitted form data from globals to given class instance
$instance = $mapper
    ->mapFromGlobals()
    ->mapTo(MyClass::class);

var_dump($instance);
// object(MyClass)#10 (4) {
//   ["foo":"MyClass":private] => int(1)
//   ["bar":"MyClass":private] => string(1) "1"
//   ["baz":"MyClass":private] => bool(true)
//   ["foofloat":"MyClass":private] => float(1)
// }