<?php declare(strict_types=1);

namespace Zerai\OpenSwoole\Monitor;

use Zerai\OpenSwoole\FileWatcher\FileWatcherInterface;

class Monitor implements MonitorInterface
{
    private FileWatcherInterface $watcher;

    public function __construct(FileWatcherInterface $watcher)
    {
        $this->watcher = $watcher;
    }

    public function filesystemIsChanged(): bool
    {
        return $this->watcher->readChangedFilePaths() !== [];
    }
}
