<?php declare(strict_types=1);

namespace Zerai\OpenSwoole\Tests\Integration;

use function fclose;
use function fwrite;
use PHPUnit\Framework\TestCase;
use function stream_get_meta_data;
use function tmpfile;
use Zerai\OpenSwoole\FileWatcher\InotifyFileWatcher;

class InotifyFileWatcherTest extends TestCase
{
    /**
     * @var resource
     */
    private $file;

    protected function setUp(): void
    {
        if (! \extension_loaded('inotify')) {
            static::markTestSkipped('The Inotify extension is not available');
        }

        $file = tmpfile();
        if (false === $file) {
            static::markTestSkipped('Unable to create a temporary file');
        }

        $this->file = $file;

        parent::setUp();
    }

    public function testReadChangedFilePathsIsNonBlocking(): void
    {
        /** @psalm-var non-empty-string $path */
        $path = stream_get_meta_data($this->file)['uri'];
        $subject = new InotifyFileWatcher();
        $subject->addFilePath($path);

        static::assertEmpty($subject->readChangedFilePaths());
        fwrite($this->file, 'foo');
        static::assertEquals([$path], $subject->readChangedFilePaths());
    }

    protected function tearDown(): void
    {
        fclose($this->file);
        parent::tearDown();
    }
}
