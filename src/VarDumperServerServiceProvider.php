<?php

namespace Rareloop\Lumberjack\VarDumperServer;

use Rareloop\Hatchet\Hatchet;
use Rareloop\Lumberjack\Providers\ServiceProvider;
use Rareloop\Lumberjack\VarDumperServer\Command;
use Rareloop\Lumberjack\VarDumperServer\Dumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Server\Connection;
use Symfony\Component\VarDumper\Server\DumpServer;
use Symfony\Component\VarDumper\VarDumper;

class VarDumperServerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'debug-server');
        // $this->app->bind('command.dumpserver', DumpServerCommand::class);
        // $this->commands([
        //     'command.dumpserver',
        // ]);
        // $host = config('debug-server.host');
        $this->app->bind(DumpServer::class, function () {
            return new DumpServer('tcp://0.0.0.0:9912');
        });
    }

    public function boot()
    {
        if ($this->app->has(Hatchet::class) && in_array(PHP_SAPI, ['cli', 'phpdbg'])) {
            $this->app->get(Hatchet::class)->console()->add($this->app->make(Command::class));
        }

        $connection = new Connection('tcp://0.0.0.0:9912', [
            // 'request' => new RequestContextProvider($this->app['request']),
            'source' => new SourceContextProvider('utf-8', ABSPATH),
        ]);

        VarDumper::setHandler(function ($var) use ($connection) {
            (new Dumper($connection))->dump($var);
        });
    }
}
