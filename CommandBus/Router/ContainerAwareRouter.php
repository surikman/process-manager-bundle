<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\Exception\CommandHandlerInstantiationError;
use Psr\Container\ContainerInterface;

final class ContainerAwareRouter implements RouterInterface
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param RegistryInterface  $registry
     * @param ContainerInterface $container
     */
    public function __construct(RegistryInterface $registry, ContainerInterface $container)
    {
        $this->registry = $registry;
        $this->container = $container;
    }


    /**
     * @param CommandInterface $command
     *
     * @return callable
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws CommandHandlerInstantiationError
     */
    public function match(CommandInterface $command): callable
    {
        try {
            $route = $this->registry->getRouteByCommand($command);
            $handler = $this->container->get($route->getHandlerId());
            $handlerMethod = $route->getExecutor();

            return function () use ($handler, $handlerMethod, $command) {
                return $handler->{$handlerMethod}($command);
            };
        } catch (\Throwable $e) {
            throw new CommandHandlerInstantiationError($e);
        }
    }
}
