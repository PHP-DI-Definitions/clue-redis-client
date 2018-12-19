<?php declare(strict_types=1);

use Clue\React\Redis\Client;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use WyriHaximus\React\Redis\WaitingClient\WaitingClient;

return [
    Client::class => \DI\factory(function (LoopInterface $loop, string $dsn, LoggerInterface $logger = null) {
        return WaitingClient::create($loop, $dsn, $logger);
    })
        ->parameter('dsn', \DI\get('config.redis.dsn')),
];
