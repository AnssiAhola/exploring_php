<?php

use Symfony\Component\EventDispatcher\EventDispatcher;

require_once('./vendor/autoload.php');

$dispatcher = new EventDispatcher;

$dispatcher->addListener(UserAccountCreated::class, function (UserAccountCreated $event): void {
    echo "Sending welcome email to user {$event->getUsername()}\n";
});
$dispatcher->addListener(UserAccountDeleted::class, function (UserAccountDeleted $event): void {
    $userId = $event->getUserId();
    echo "User account with id {$userId} deleted. Cleaning up...\n";
});

$userName = $argv[1];

$dispatcher->dispatch(new UserAccountCreated($userName));
$dispatcher->dispatch(new UserAccountDeleted(random_int(1, 1000)));