<?php declare(strict_types=1);

namespace Zerai\OpenSwoole\Tests\E2E;

use function OpenSwoole\Coroutine\Http\get;
use function OpenSwoole\Coroutine\run;
use PHPUnit\Framework\TestCase;

class StaticFileHandlerTest extends TestCase
{
    public function testSwooleServerHandlesStaticFiles(): void
    {
        self::markTestSkipped('verify run() openswoole documentation.');
        run(static function (): void {
            self::assertSame("Static file\n", get('http://localhost:8001/file.txt')->getBody());
        });
    }
}
