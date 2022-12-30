<?php declare(strict_types=1);

namespace Zerai\OpenSwoole;

use Illuminate\Contracts\Http\Kernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Runtime\RunnerInterface;
use Symfony\Component\Runtime\SymfonyRuntime;

/**
 * A runtime for Swoole.
 */
class Runtime extends SymfonyRuntime
{
    /**
     * @var ?ServerFactory
     */
    private $serverFactory;

    public function __construct(array $options, ?ServerFactory $serverFactory = null)
    {
        $this->serverFactory = $serverFactory ?? new ServerFactory($options);
        parent::__construct($this->serverFactory->getOptions());
    }

    public function getRunner(?object $application): RunnerInterface
    {
        if (\is_callable($application)) {
            return new CallableRunner($this->serverFactory, $application);
        }

        if ($application instanceof HttpKernelInterface) {
            return new SymfonyRunner($this->serverFactory, $application);
        }

        if ($application instanceof Kernel) {
            return new LaravelRunner($this->serverFactory, $application);
        }

        return parent::getRunner($application);
    }
}
