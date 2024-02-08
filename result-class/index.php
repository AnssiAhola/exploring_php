<?php
require_once('./vendor/autoload.php');

for ($i=0; $i < 10; $i++) { 
    $randInt = random_int(40, 45);

    if ($randInt === 42) {
        $result = Result::ok("Hello World!");
    } else {
        $result = Result::fail(null);
    }

    $response = $result->match(
        expect: ResponseInterface::class,
        onValue: fn(string $value) => new Ok($value),
        onError: fn() => new NotFound(),
    );
    echo("{$i}: {$response->getStatusCode()} {$response->getMessage()}\n");
}
