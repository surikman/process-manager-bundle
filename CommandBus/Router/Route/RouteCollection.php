<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route;

use ArrayIterator;
use IteratorAggregate;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\Exception\CommandNotFound;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\CompilableInterface;

final class RouteCollection implements IteratorAggregate, CompilableInterface
{
    /**
     * @var array|Route[]
     */
    private $routesByName = [];

    /**
     * @var array|Route[]
     */
    private $routesByFqcn = [];

    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }

    /**
     * @param Route $route
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $this->routesByName[$route->getCommandName()] = $route;
        $this->routesByFqcn[$route->getCommandClass()] = $route;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->routesByName);
    }

    /**
     * @param string $name
     *
     * @throws CommandNotFound
     * @return Route
     */
    public function getByName(string $name): Route
    {
        if (!isset($this->routesByName[$name])) {
            throw new CommandNotFound();
        }

        return $this->routesByName[$name];
    }

    /**
     * @param CommandInterface $command
     *
     * @return Route
     * @throws CommandNotFound
     */
    public function getByCommand(CommandInterface $command): Route
    {
        $commandClass = get_class($command);

        if (!isset($this->routesByFqcn[$commandClass])) {
            throw new CommandNotFound();
        }

        return $this->routesByFqcn[$commandClass];
    }

    /**
     * @return array
     */
    public function compile(): array
    {
        $routes = [];
        foreach ($this as $route) {
            $routes[$route->getHandlerId()] = $route->compile();
        }

        return $routes;
    }
}
