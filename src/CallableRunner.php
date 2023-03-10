<?php declare(strict_types=1);

namespace Zerai\OpenSwoole;

use Symfony\Component\Runtime\RunnerInterface;

/**
 * A simple runner that will run a callable.
 */
class CallableRunner implements RunnerInterface
{
    /**
     * @var ServerFactory
     */
    private $serverFactory;

    /**
     * @var callable
     */
    private $application;

    public function __construct(ServerFactory $serverFactory, callable $application)
    {
        $this->serverFactory = $serverFactory;
        $this->application = $application;
    }

    public function run(): int
    {
        $this->serverFactory->createServer($this->application)->start();

        return 0;
    }
}
