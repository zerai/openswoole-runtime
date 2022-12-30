<?php declare(strict_types=1);

namespace Zerai\OpenSwoole\Monitor;

interface MonitorInterface
{
    public function filesystemIsChanged(): bool;
}
