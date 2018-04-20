<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\CommandBus;

use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route\Route;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RouterInterface;
use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage;

/**
 *
 */
final class RouterFactory
{
    /**
     * @param array              $configuration
     * @param DomainEventStorage $eventStorage
     *
     * @return RouterInterface
     */
    public static function create(array $configuration, DomainEventStorage $eventStorage): RouterInterface
    {
        $routes = [];
        foreach ($configuration as $command => $commandHandler) {
            $routes[] = new Route($command, $command, $commandHandler, 'handle');
        }

        return new BasicRouter($eventStorage, ...$routes);
    }

}
