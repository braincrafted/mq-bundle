<?php
/**
 * This file is part of BcMqBundle.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Bc\Bundle\MqBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @package    BcMqBundle
 * @subpackage DependencyInjection
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bc_mq');

        $rootNode
            ->children()
                ->arrayNode('consumers')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('producer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('hostname')->defaultValue('localhost')->end()
                        ->scalarNode('port')->defaultValue(4000)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
