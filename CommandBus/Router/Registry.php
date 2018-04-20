<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\Exception\CommandNotFound;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector\CommandHandlerCollector;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route\Route;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route\RouteCollection;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route\RouteFactoryInterface;

/**
 *
 */
final class Registry implements RegistryInterface, CompilableInterface
{
    /**
     * @var RouteCollection
     */
    private $collection;
    /**
     * @var RouteFactoryInterface
     */
    private $routeFactory;
    /**
     * @var CommandHandlerCollector
     */
    private $collector;

    /**
     * @param RouteFactoryInterface   $routeFactory
     * @param CommandHandlerCollector $collector
     */
    public function __construct(RouteFactoryInterface $routeFactory, CommandHandlerCollector $collector)
    {
        $this->routeFactory = $routeFactory;
        $this->collector = $collector;
    }

    /**
     * @param string $commandName
     *
     * @return Route
     * @throws CommandNotFound
     */
    public function getRouteByCommandName(string $commandName): Route
    {
        return $this->getRouteCollection()->getByName($commandName);
    }

    /**
     * @param CommandInterface $command
     *
     * @return Route
     * @throws CommandNotFound
     */
    public function getRouteByCommand(CommandInterface $command): Route
    {
        return $this->getRouteCollection()->getByCommand($command);
    }

    /**
     * @return array
     * @throws CommandNotFound
     */
    public function compile(): array
    {
        return $this->getRouteCollection()->compile();
    }

    /**
     * @return RouteCollection
     * @throws CommandNotFound
     */
    private function getRouteCollection(): RouteCollection
    {
        if (null !== $this->collection) {
            return $this->collection;
        }

        return $this->collection = $this->createCollection();
    }

    /**
     * @return RouteCollection
     * @throws CommandNotFound
     */
    private function createCollection(): RouteCollection
    {
        $collection = new RouteCollection();
        foreach ($this->collector as $handlerDefinition) {
            $route = $this->routeFactory->create($handlerDefinition);
            $collection->addRoute($route);
        }

        return $collection;
    }
}
