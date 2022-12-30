<?php declare(strict_types=1);

namespace Zerai\OpenSwoole;

use OpenSwoole\Http\Server;
use Zerai\OpenSwoole\FileWatcher\InotifyFileWatcher;
use Zerai\OpenSwoole\Monitor\Monitor;

class ServerFactory
{
    private const DEFAULT_OPTIONS = [
        'host' => '127.0.0.1',
        'port' => 8000,
        'mode' => 2, // SWOOLE_PROCESS
        'sock_type' => 1, // SWOOLE_SOCK_TCP
        'hot_reload' => false,
        'hot_reload_interval' => 2000,
        'settings' => [],
    ];

    /**
     * @var array
     */
    private $options;

    public static function getDefaultOptions(): array
    {
        return self::DEFAULT_OPTIONS;
    }

    public function __construct(array $options = [])
    {
        $options['host'] = $options['host'] ?? $_SERVER['SWOOLE_HOST'] ?? $_ENV['SWOOLE_HOST'] ?? self::DEFAULT_OPTIONS['host'];
        $options['port'] = $options['port'] ?? $_SERVER['SWOOLE_PORT'] ?? $_ENV['SWOOLE_PORT'] ?? self::DEFAULT_OPTIONS['port'];
        $options['mode'] = $options['mode'] ?? $_SERVER['SWOOLE_MODE'] ?? $_ENV['SWOOLE_MODE'] ?? self::DEFAULT_OPTIONS['mode'];
        $options['sock_type'] = $options['sock_type'] ?? $_SERVER['SWOOLE_SOCK_TYPE'] ?? $_ENV['SWOOLE_SOCK_TYPE'] ?? self::DEFAULT_OPTIONS['sock_type'];
        $options['hot_reload'] = $options['hot_reload'] ?? $_SERVER['SWOOLE_HOT_RELOAD'] ?? $_ENV['SWOOLE_HOT_RELOAD'] ?? self::DEFAULT_OPTIONS['hot_reload'];
        $options['hot_reload_interval'] = $options['hot_reload_interval'] ?? $_SERVER['SWOOLE_HOT_RELOAD_INTERVAL'] ?? $_ENV['SWOOLE_HOT_RELOAD_INTERVAL'] ?? self::DEFAULT_OPTIONS['hot_reload_interval'];

        $this->options = array_replace_recursive(self::DEFAULT_OPTIONS, $options);
    }

    public function createServer(callable $requestHandler): Server
    {
        if ((bool) $this->options['hot_reload']) {
            return $this->createServerWithHotReload($requestHandler);
        }

        return $this->createServerAsDefault($requestHandler);
    }

    public function createServerAsDefault(callable $requestHandler): Server
    {
        $server = new Server($this->options['host'], (int) $this->options['port'], (int) $this->options['mode'], (int) $this->options['sock_type']);
        $server->set($this->options['settings']);
        $server->on('request', $requestHandler);

        return $server;
    }

    public function createServerWithHotReload(callable $requestHandler): Server
    {
        $fileWatcher = new InotifyFileWatcher();
        $fileWatcher->addFilePath($this->options['project_dir']);
        $monitor = new Monitor($fileWatcher);

        $server = new Server($this->options['host'], (int) $this->options['port'], (int) $this->options['mode'], (int) $this->options['sock_type']);
        $server->set($this->options['settings']);
        $server->tick((int) $this->options['hot_reload_interval'], function () use ($server, $monitor): void {
            if ($monitor->filesystemIsChanged()) {
                $server->reload();
            }
        });
        $server->on('request', $requestHandler);

        return $server;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
