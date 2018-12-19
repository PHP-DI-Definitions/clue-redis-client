<?php declare(strict_types=1);

namespace PHPDIDefinitions\Tests\Clue\Redis\Client;

use ApiClients\Tools\TestUtilities\TestCase;
use Clue\React\Redis\Client;
use DI\Container;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;

final class DiTest extends TestCase
{
    private const DSN = 'redis://::1/13';

    public function testCreate()
    {
        $loop = $this->prophesize(LoopInterface::class);

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->debug('Connecting')->shouldBeCalled();
        $logger->debug('Connected')->shouldNotBeCalled();
        $logger->debug('Executing 0 waiting call(s)')->shouldNotBeCalled();
        $logger->debug('Executed all waiting calls, any new calls will be send to Redis directly')->shouldNotBeCalled();

        $di = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'di' . DIRECTORY_SEPARATOR . 'redis-client.php';
        $container = new Container();
        $container->set(Client::class, $di[Client::class]);
        $container->set(LoopInterface::class, $loop->reveal());
        $container->set(LoggerInterface::class, $logger->reveal());
        $container->set('config.redis.dsn', self::DSN);
        $client = $container->get(Client::class);

        self::assertInstanceOf(Client::class, $client);
    }
}
