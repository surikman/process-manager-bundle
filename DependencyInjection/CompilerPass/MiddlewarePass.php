<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\DependencyInjection\CompilerPass;

use SuRiKmAn\ProcessManagerBundle\CommandBus\LeagueCommandBusFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MiddlewarePass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @param ContainerBuilder $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\OutOfBoundsException
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('surikman_process_manager.command_bus.middleware');
        $factory = $container->findDefinition(LeagueCommandBusFactory::class);

        $middleware = [];
        foreach ($taggedServices as $id => $tags) {
            $service = $container->findDefinition($id)->setLazy(true);
            $middleware[] = $service;
        }

        $factory->replaceArgument('$services', $middleware);
    }
}
