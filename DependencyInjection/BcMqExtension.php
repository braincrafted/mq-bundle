<?php
/**
 * This file is part of BcMqBundle.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Bc\Bundle\MqBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BcMqExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        foreach (array('server', 'consumer', 'producer') as $file) {
            $loader->load(sprintf('%s.xml', $file));
        }

        if (isset($config['consumers']) && is_array($config['consumers'])) {
            $container->setParameter('bc_mq.consumers', $config['consumers']);
        }

        if (isset($config['producer']['hostname'])) {
            $container->setParameter('bc_mq.producer.hostname', $config['producer']['hostname']);
        }

        if (isset($config['producer']['port'])) {
            $container->setParameter('bc_mq.producer.port', $config['producer']['port']);
        }
    }
}
