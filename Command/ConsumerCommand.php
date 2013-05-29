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
 * ConsumerCommand.
 *
 * @package    BcMqBundle
 * @subpackage Command
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class ConsumerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('bc:mq:consumer')
            ->setDescription('Message Consumer')
            ->addArgument('message', InputArgument::REQUIRED, 'The consumed message')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumer = $this->getContainer()->get('bc_mq.consumer');
        $consumer->setContainer($this->getContainer());

        $consumer->consume($input->getArgument('message'));
    }
}