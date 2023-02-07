<?php
require_once('./vendor/autoload.php');

class IncrementorMiddleware
{

    public function __construct(
        private int $amount = 1
    ) {
    }

    public function __invoke(int $value, callable $next): int
    {
        $output = $next($value);
        return $output + $this->amount;
    }
}

$middlewares = [
    new IncrementorMiddleware(),
    new IncrementorMiddleware(10),
    new IncrementorMiddleware(-3)
];

$action = fn (int $value): int => $value;

foreach ($middlewares as $middleware) {
    $action = fn (int $value): int => $middleware($value, $action);
}

print($action(1)); // 9
