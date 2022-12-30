<?php declare(strict_types=1);

namespace Zerai\OpenSwoole;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Runtime\RunnerInterface;

/**
 * A runner for Symfony.
 */
class SymfonyRunner implements RunnerInterface
{
    /**
     * @var ServerFactory
     */
    private $serverFactory;

    /**
     * @var HttpKernelInterface
     */
    private $application;

    public function __construct(ServerFactory $serverFactory, HttpKernelInterface $application)
    {
        $this->serverFactory = $serverFactory;
        $this->application = $application;
    }

    public function run(): int
    {
        $this->serverFactory->createServer([$this, 'handle'])->start();

        return 0;
    }

    public function handle(Request $request, Response $response): void
    {
        $sfRequest = SymfonyHttpBridge::convertSwooleRequest($request);

        $sfResponse = $this->application->handle($sfRequest);
        SymfonyHttpBridge::reflectSymfonyResponse($sfResponse, $response);

        if ($this->application instanceof TerminableInterface) {
            $this->application->terminate($sfRequest, $sfResponse);
        }
    }
}
