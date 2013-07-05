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

/**
 * ServerCommand.
 *
 * @package    BcMqBundle
 * @subpackage Command
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class ServerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('bc:mq:server')
            ->setDescription('Message Queue Server')
            ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'The port the server should listen on')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getOption('port');
        $server = $this->getContainer()->get('bc_mq.server');

        $output->writeln(sprintf('Starting Message Queue Server at port %d', $port));

        // If the verbose option is given, show received data in the command line.
        if ($input->getOption('verbose')) {
            $callback = function ($data) use ($output) {
                $output->writeln(sprintf('<comment>Received data:</comment> %s', $data));
            };
        }

        $server->run(
            sprintf(
                '`which php` %s/console bc:mq:consumer',
                $this->getContainer()->getParameter('kernel.root_dir')
            ),
            $port,
            $callback
        );

    }
}