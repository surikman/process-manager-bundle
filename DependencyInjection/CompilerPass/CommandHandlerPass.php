<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\DependencyInjection\CompilerPass;

use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector\CommandHandlerCollector;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector\CommandHandlerDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class CommandHandlerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @param ContainerBuilder $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('surikman_process_manager.command_bus.command_handler');
        $serviceIds = [];
        foreach ($taggedServices as $id => $tags) {
            $definition = $container->findDefinition($id);
            $definition->setPublic(true); // make this service as public, because later will be lazy loaded
            $serviceIds[] = new Definition(
                CommandHandlerDefinition::class,
                [ $id, $definition->getClass() ]
            );
        }

        $container->setDefinition(
            CommandHandlerCollector::class,
            new Definition(CommandHandlerCollector::class, [ $serviceIds ])
        );
    }
}
