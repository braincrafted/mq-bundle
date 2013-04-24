<?php
/**
 * This file is part of BcMqBundle.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Bc\Bundle\MqBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bc:mq:server')
            ->setDescription('Message Queue Server')
            ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'The port the server should listen on')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getOption('port');
        $server = $this->getContainer()->get('bc_mq.server');

        $output->writeln(sprintf('Starting Message Queue Server at port %d', $port));

        $server->run(
            sprintf(
                '`which php` %s/console bc:mq:consumer',
                $this->getContainer()->getParameter('kernel.root_dir')
            ),
            $port
        );

    }
}