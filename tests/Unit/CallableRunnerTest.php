<?php

declare(strict_types=1);

namespace Zerai\OpenSwoole\Tests\Unit;

use OpenSwoole\Http\Server;
use PHPUnit\Framework\TestCase;
use Zerai\OpenSwoole\CallableRunner;
use Zerai\OpenSwoole\ServerFactory;

class CallableRunnerTest extends TestCase
{
    public function testRun(): void
    {
        $application = static function (): void {
        };

        $server = $this->createMock(Server::class);
        $server->expects(self::once())->method('start');

        $factory = $this->createMock(ServerFactory::class);
        $factory->expects(self::once())->method('createServer')->with(self::equalTo($application))->willReturn($server);

        $runner = new CallableRunner($factory, $application);

        self::assertSame(0, $runner->run());
    }
}
