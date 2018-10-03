<?php declare(strict_types=1);

namespace PHPDIDefinitions\Clue\Redis\Client;

use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use Evenement\EventEmitterTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use SplQueue;
use WyriHaximus\PSR3\CallableThrowableLogger\CallableThrowableLogger;

final class WaitingClient implements Client
{
    use EventEmitterTrait;

    /**
     * @var Client
     */
    private $redis;

    /**
     * @var SplQueue
     */
    private $callQueue;

    /**
     * @param Factory         $factory
     * @param string          $dsn
     * @param LoggerInterface $logger
     *
     * @internal
     */
    public function __construct(Factory $factory, string $dsn, LoggerInterface $logger)
    {
        $this->callQueue = new \SplQueue();
        $logger->debug('Connecting');
        $factory->createClient($dsn)->done(function (Client $client) use ($logger) {
            $logger->debug('Connected');
            $this->redis = $client;

            $logger->debug('Executing ' . $this->callQueue->count() . ' waiting call(s)');
            while ($this->callQueue->count() > 0) {
                $call = $this->callQueue->dequeue();
                $name = $call['name'];
                $args = $call['args'];
                $call['deferred']->resolve($this->redis->$name(...$args));
            }
            $logger->debug('Executed all waiting calls, any new calls will be send to Redis directly');
        }, CallableThrowableLogger::create($logger));
    }

    public function __call($name, $args)
    {
        if ($this->redis instanceof Client) {
            return $this->redis->$name(...$args);
        }

        $deferred = new Deferred();

        $this->callQueue->enqueue([
            'deferred' => $deferred,
            'name' => $name,
            'args' => $args,
        ]);

        return $deferred->promise();
    }

    public static function create(LoopInterface $loop, string $dsn, LoggerInterface $logger = null): self
    {
        return new self(new Factory($loop), $dsn, $logger ?? new NullLogger());
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
