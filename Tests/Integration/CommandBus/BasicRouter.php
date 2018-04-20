<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\CommandBus;

use InvalidArgumentException;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route\RouteInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RouterInterface;
use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage;

/**
 *
 */
final class BasicRouter implements RouterInterface
{
    /**
     * @var array
     */
    private $routes;
    /**
     * @var DomainEventStorage
     */
    private $eventStorage;

    /**
     * @param DomainEventStorage $eventStorage
     * @param RouteInterface[]   ...$routes
     */
    public function __construct(DomainEventStorage $eventStorage, RouteInterface ...$routes)
    {
        foreach ($routes as $route) {
            $this->routes[$route->getCommandClass()] = $route;
        }
        $this->eventStorage = $eventStorage;
    }

    /**
     * @param CommandInterface $command
     *
     * @return callable
     */
    public function match(CommandInterface $command): callable
    {
        /** @var RouteInterface|null $route */
        $route = $this->routes[get_class($command)] ?? null;
        if (!$route) {
            throw new InvalidArgumentException('Unknown route');
        }

        return function () use ($route, $command) {
            $handlerId = $route->getHandlerId();
            $handler = new $handlerId($this->eventStorage);
            $handlerMethod = $route->getExecutor();

            return $handler->{$handlerMethod}($command);
        };
    }
}
