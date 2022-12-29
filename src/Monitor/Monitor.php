<?php

namespace Runtime\Swoole\Monitor;

use Runtime\Swoole\FileWatcher\FileWatcherInterface;

class Monitor implements MonitorInterface
{
    private FileWatcherInterface $watcher;

    /**
     * @param FileWatcherInterface $watcher
     */
    public function __construct(FileWatcherInterface $watcher)
    {
        $this->watcher = $watcher;
    }

    public function filesystemIsChanged(): bool
    {
        return $this->watcher->readChangedFilePaths() !== [];
    }
}