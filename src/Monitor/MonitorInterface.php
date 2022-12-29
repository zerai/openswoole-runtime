<?php

namespace Runtime\Swoole\Monitor;

interface MonitorInterface
{
    public function filesystemIsChanged(): bool;
}