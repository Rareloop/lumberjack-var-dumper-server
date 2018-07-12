<?php

namespace Rareloop\Lumberjack\VarDumperServer;

use Rareloop\Hatchet\Commands\Command as HatchetCommand;
use Rareloop\Lumberjack\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Command\Descriptor\CliDescriptor;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Server\DumpServer;

class Command extends HatchetCommand
{
    protected $signature = 'dump-server';

    protected $server;

    public function __construct(DumpServer $server, Application $app)
    {
        $this->server = $server;

        parent::__construct($app);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $descriptor = new CliDescriptor(new CliDumper());

        $this->server->start();
        $output->writeln('Laravel Var Dump Server');
        $output->writeln(sprintf('Server listening on %s', $this->server->getHost()));
        $output->writeln('Quit the server with CONTROL-C.');

        $this->server->listen(function (Data $data, array $context, int $clientId) use ($descriptor, $output) {
            $descriptor->describe($output, $data, $context, $clientId);
        });
    }
}
