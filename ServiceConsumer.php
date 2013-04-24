<?php
/**
 * This file is part of BcMqBundle.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Bc\Bundle\MqBundle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Bc\Json\Json;
use Bc\Mq\Consumer;

class ServiceConsumer implements ContainerAwareInterface
{
    /** @var array */
    private $consumers;

    /** @var ContainerInterface */
    private $container;

    /**
     * Constructor.
     *
     * @param array $consumers Mesage consumers, associative array where the key is the type and the value
     *                         is a callback
     */
    public function __construct(array $consumers = array())
    {
        $this->consumers = $consumers;
    }

    /**
     * Sets the service container.
     *
     * @param ContainerInterface $container The service container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Consumes the given message.
     *
     * @param string $data The message must be encoded as JSON and quotes can be escaped.
     *
     * @return void
     */
    public function consume($data)
    {
        $data = Json::decode(stripslashes($data), true);

        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('Message can\'t be consumed because the type information is missing.');
        }

        if (!isset($data['message'])) {
            throw new \InvalidArgumentException('Message can\'t be consumed because the message is missing.');
        }

        if (isset($this->consumers[$data['type']])) {
            print_r($data);
            $service = $this->container->get($this->consumers[$data['type']]);
            call_user_func(array($service, 'consume'), $data['message']);
        }
    }
}
