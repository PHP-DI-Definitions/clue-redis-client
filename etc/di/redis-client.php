<?php declare(strict_types=1);

use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectorInterface;

return [
    Client::class => \DI\factory(function (LoopInterface $loop, ConnectorInterface $connector, string $dsn) {
        return (new Factory($loop, $connector))->createLazyClient($dsn);
    })
        ->parameter('dsn', \DI\get('config.redis.dsn')),
    'redis.client.read' => \DI\factory(function (LoopInterface $loop, ConnectorInterface $connector, string $dsn) {
        return (new Factory($loop, $connector))->createLazyClient($dsn);
    })
        ->parameter('dsn', \DI\get('config.redis.read.dsn')),
    'redis.client.write' => \DI\factory(function (LoopInterface $loop, ConnectorInterface $connector, string $dsn) {
        return (new Factory($loop, $connector))->createLazyClient($dsn);
    })
        ->parameter('dsn', \DI\get('config.redis.write.dsn')),
];
