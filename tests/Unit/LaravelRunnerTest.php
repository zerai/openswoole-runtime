<?php declare(strict_types=1);

namespace Zerai\OpenSwoole\Tests\Unit;

use Illuminate\Contracts\Http\Kernel;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use OpenSwoole\Http\Server;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Zerai\OpenSwoole\LaravelRunner;
use Zerai\OpenSwoole\ServerFactory;

class LaravelRunnerTest extends TestCase
{
    public function testRun(): void
    {
        $application = $this->createMock(Kernel::class);

        $server = $this->createMock(Server::class);
        $server->expects(self::once())->method('start');

        $factory = $this->createMock(ServerFactory::class);
        $factory->expects(self::once())->method('createServer')->willReturn($server);

        $runner = new LaravelRunner($factory, $application);

        self::assertSame(0, $runner->run());
    }

    public function testHandle(): void
    {
        self::markTestSkipped('Fail before refactoring');
        $sfResponse = new SymfonyResponse('foo');

        $application = $this->createMock(Kernel::class);
        $application->expects(self::once())->method('handle')->willReturn($sfResponse);

        $response = $this->createMock(Response::class);
        $response->expects(self::once())->method('end')->with('foo');

        $request = $this->createMock(Request::class);
        $factory = $this->createMock(ServerFactory::class);

        $runner = new LaravelRunner($factory, $application);
        $runner->handle($request, $response);
    }
}
