<?php declare(strict_types=1);

namespace PHPDIDefinitions\Tests\Clue\Redis\Client;

use Clue\React\Redis\Client;
use Evenement\EventEmitterTrait;

class RedisClient implements Client
{
    use EventEmitterTrait;

    public function __call($name, $args)
    {
        // TODO: Implement __call() method.
    }

    public function incr(...$args)
    {
    }

    public function end()
    {
        // TODO: Implement end() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}
