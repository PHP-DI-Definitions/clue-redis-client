<?php declare(strict_types=1);

use Clue\React\Redis\Client;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use WyriHaximus\React\Redis\WaitingClient\WaitingClient;
use function DI\factory;
use function DI\get;

return [
    Client::class => factory(function (LoopInterface $loop, string $dsn, LoggerInterface $logger = null) {
        return WaitingClient::create($loop, $dsn, $logger);
    })
        ->parameter('dsn', get('config.redis.dsn')),
];
