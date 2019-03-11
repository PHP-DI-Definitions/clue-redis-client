<?php declare(strict_types=1);

use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use React\EventLoop\LoopInterface;

return [
    Client::class => \DI\factory(function (LoopInterface $loop, string $dsn) {
        return (new Factory($loop))->createLazyClient($dsn);
    })
        ->parameter('dsn', \DI\get('config.redis.dsn')),
];
