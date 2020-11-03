<?php

namespace RevisionTen\Rapidmail\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        // Todo: https://symfony.com/blog/new-in-symfony-4-2-important-deprecations?#deprecated-tree-builders-without-root-nodes
        $rootNode = $treeBuilder->root('rapidmail');
        $rootNode
            ->children()
                ->scalarNode('api_username_hash')->end()
                ->scalarNode('api_password_hash')->end()
                ->arrayNode('campaigns')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('list_id')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
