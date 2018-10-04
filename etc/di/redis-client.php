<?php

use Clue\React\Redis\Client;
use PHPDIDefinitions\Clue\Redis\Client\WaitingClient;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;

return [
    Client::class => \DI\factory(function (LoopInterface $loop, string $dsn, LoggerInterface $logger = null) {
        return WaitingClient::create($loop, $dsn, $logger);
    })
        ->parameter('dsn', \DI\get('config.redis.dsn')),
];
