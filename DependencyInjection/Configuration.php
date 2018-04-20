<?php
declare(strict_types=1);

namespace SuRiKmAn\ProcessManagerBundle\DependencyInjection;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\NodeInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('surikman_process_manager');

        $rootNode
            ->children()
                ->arrayNode('services')->canBeEnabled()
                    ->children()
                        ->scalarNode('logger_service')->defaultNull()->end()
                        ->scalarNode('command_bus_service')->defaultNull()->end()
                        ->scalarNode('router_service')->defaultNull()->end()
                        ->scalarNode('route_factory_service')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        $this->addProcessManagerConfiguration($rootNode);

        return $treeBuilder;
    }



    /**
     * @param ArrayNodeDefinition $rootNode
     *
     * @return void
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    private function addProcessManagerConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $this->buildPathNode(
            $rootNode
                ->children()
                    ->arrayNode('process_manager')->canBeEnabled()
                        ->children()
                        ->arrayNode('processes')
                            ->arrayPrototype()
                                ->scalarPrototype()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('extended_processes')
                            ->arrayPrototype()
        )
                        ->end()
                    ->end()
                ->end()
            ->end();

    }

    /**
     * @param mixed $child
     * @param string $name
     *
     * @return void
     */
    protected function evaluateSub(&$child, string $name): void
    {
        $child = $this->getPathNode($name)->finalize($child);
    }

    /**
     * @param NodeDefinition $node
     *
     * @return mixed
     */
    protected function buildPathNode(NodeDefinition $node): NodeDefinition
    {
        return $node
            ->addDefaultsIfNotSet()
                ->children()
                ->arrayNode('main')
                    ->scalarPrototype()->end()
                ->end()
                ->variableNode('sub')
                ->defaultValue([])
                ->validate()->ifTrue(function($element) { return !is_array($element); })->thenInvalid('The sub element must be an array.')->end()
                ->validate()->always(function($sub) {array_walk($sub, array($this, 'evaluateSub'));return $sub;})->end()
                ->end()
            ->end()
            ;
    }

    /**
     * @param string $name
     *
     * @return NodeInterface
     */
    protected function getPathNode(string $name): NodeInterface
    {
        $treeBuilder = new TreeBuilder();
        $definition = $treeBuilder->root($name);

        $this->buildPathNode($definition);

        return $definition->getNode(true);
    }

}
