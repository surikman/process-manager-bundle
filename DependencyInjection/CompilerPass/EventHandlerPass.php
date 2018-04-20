<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\DependencyInjection\CompilerPass;

use ReflectionClass;
use ReflectionException;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Handler\EventHandlerInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Handler\SubscribedEventHandlerInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

final class EventHandlerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @param ContainerBuilder $container
     *
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     * @throws BadMethodCallException
     * @throws ReflectionException
     * @throws InvalidConfigurationException
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('surikman_process_manager.event_bus.event_handler');
        $eventBus = $container->findDefinition(EventBusInterface::class);
        foreach ($taggedServices as $id => $tags) {
            $definition = $container->findDefinition($id);
            $reflection = new ReflectionClass($definition->getClass());
            if (!$reflection->implementsInterface(EventHandlerInterface::class)) {
                continue; // skip...
            }
            $canBeLazy = $reflection->isFinal() ? false : true;
            $definition->setLazy($canBeLazy);
            $className = $reflection->getName();

            if ($reflection->implementsInterface(SubscribedEventHandlerInterface::class)) {
                /** @var array $subscribedEvents */
                $subscribedEvents = $className::getSubscribedEvents();
                foreach ($subscribedEvents as $eventName => $params) {
                    if (is_string($params)) {
                        $eventBus->addMethodCall(
                            'addHandler',
                            [ $eventName, new Reference($id), $params ]
                        );
                    } elseif (is_string($params[0])) {
                        $eventBus->addMethodCall(
                            'addHandler',
                            [ $eventName, new Reference($id), $params[0], $params[1] ?? 0 ]
                        );
                    } else {
                        foreach ($params ?: [] as $listener) {
                            $eventBus->addMethodCall(
                                'addHandler',
                                [ $eventName, new Reference($id), $listener, $listener[1] ?? 0 ]
                            );
                        }
                    }
                }
            } else {
                foreach ($tags as $attributes) {
                    if (empty($attributes)) {
                        continue;
                    }
                    $eventName = $attributes['event'] ?? $attributes['eventName'] ?? $attributes['event_name'] ?? null;
                    $method = $attributes['method'] ?? null;
                    if (null === $eventName || null === $method) {
                        throw new InvalidConfigurationException('event and method are mandatory attributes for tag "surikman_process_manager.event_bus.event_handler"');
                    }
                    $priority = $attributes['priority'] ?? 0;
                    $eventBus->addMethodCall('addHandler', [ $eventName, new Reference($id), $method, $priority ]);
                }
            }
        }
    }
}
