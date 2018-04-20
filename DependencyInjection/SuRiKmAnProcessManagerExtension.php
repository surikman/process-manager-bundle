<?php
declare(strict_types=1);

namespace SuRiKmAn\ProcessManagerBundle\DependencyInjection;

use Exception;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandBusInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RouterInterface;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessConfiguration;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessConfigurationFactory;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessManager;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SuRiKmAnProcessManagerExtension extends ConfigurableExtension
{

    /**
     * @param array            $mergedConfig
     * @param ContainerBuilder $container
     *
     * @throws ServiceNotFoundException
     * @throws BadMethodCallException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('event_bus.yaml');
        $loader->load('command_bus.yaml');
        $loader->load('process_manager.yaml');
        $loader->load('public_services.yaml');

        $this->processServices($mergedConfig['services'], $container);
        $this->processProcessManager($mergedConfig['process_manager'], $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function processServices(array $config, ContainerBuilder $container): void
    {
        $this->createAlias('surikman_process_manager.logger', $config['logger_service'], $container);
        $this->createAlias(CommandBusInterface::class, $config['command_bus_service'], $container);
        $this->createAlias(RouterInterface::class, $config['router_service'], $container);
    }

    /**
     * @param array            $processManagerConfig
     * @param ContainerBuilder $container
     *
     * @return void
     * @throws ServiceNotFoundException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    private function processProcessManager(array $processManagerConfig, ContainerBuilder $container): void
    {
        if ($processManagerConfig['enabled'] === false) {
            return;
        }
        $this->processExtendedProcesses($container, $processManagerConfig['extended_processes']);
        $this->processSimpleProcesses($container, $processManagerConfig['processes']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $processes
     *
     * @return void
     * @throws ServiceNotFoundException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    private function processExtendedProcesses(ContainerBuilder $container, array $processes): void
    {
        $processServiceNamePattern = 'surikman_process_manager.process_manager.%s';
        foreach ($processes as $processName => $configuration) {

            [ $events, $subProcesses ] = $this->createProcessConfiguration($configuration);

            $processConfigurationDefinition = new Definition(ProcessConfiguration::class);
            $processConfigurationDefinition->setFactory([
                new Reference(ProcessConfigurationFactory::class),
                'create',
            ]);
            $processConfigurationDefinition->setArgument('$events', $events);
            $processConfigurationDefinition->setArgument('$subProcesses', $subProcesses);

            $serviceId = sprintf($processServiceNamePattern, $processName);
            $child = new ChildDefinition(ProcessManager::class);
            $child->setClass(ProcessManager::class);
            $child->replaceArgument('$configuration', $processConfigurationDefinition);


            // process eventBus event handling first event
            $firstEvent = key($events);
            $child->addTag('surikman_process_manager.event_bus.event_handler', [
                'event'  => $firstEvent,
                'method' => 'handle',
            ]);
            $container->setDefinition($serviceId, $child);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $processes
     *
     * @return void
     * @throws ServiceNotFoundException
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    private function processSimpleProcesses(ContainerBuilder $container, array $processes): void
    {
        foreach ($processes as $processName => $configuration) {
            $this->processExtendedProcesses($container, [
                $processName => [
                    'main' => $configuration,
                ],
            ]);
        }
    }


    /**
     * @param array $configuration
     *
     * @return array
     */
    private function createProcessConfiguration(array $configuration): array
    {
        $events = [];
        $subProcesses = [];
        foreach ($configuration as $type => $configurationOfType) {
            if ($type === 'main') {
                foreach ($configurationOfType ?: [] as $event => $commandTransformer) {
                    $events[$event] = new Reference($commandTransformer);
                }
            } elseif ($type === 'sub') {
                foreach ($configurationOfType ?: [] as $subTypeConfiguration) {
                    $subProcesses[] = $this->createProcessConfiguration($subTypeConfiguration);
                }
            }
        }

        return [ $events, $subProcesses ];
    }

    /**
     * @param string           $alias
     * @param null|string      $service
     * @param ContainerBuilder $container
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function createAlias(string $alias, ?string $service, ContainerBuilder $container): void
    {
        if (null === $service) {
            return;
        }
        $serviceId = $service;
        if (strpos($service, '@') === 0) { // starts with @
            $serviceId = substr($service, 1);
        }
        $container->setAlias($alias, $serviceId);
    }
}
