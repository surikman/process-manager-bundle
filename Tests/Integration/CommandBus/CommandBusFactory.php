<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\CommandBus;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandBus;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandBusInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\DomainEventDispatcherCommandBusDecorator;
use SuRiKmAn\ProcessManagerBundle\CommandBus\LeagueCommandBusFactory;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Middleware\RouterMiddleware;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RouterInterface;
use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;

/**
 *
 */
final class CommandBusFactory
{
    /**
     * @param RouterInterface    $router
     * @param EventBusInterface  $eventBus
     *
     * @param DomainEventStorage $eventStorage
     *
     * @return CommandBusInterface
     */
    public static function create(
        RouterInterface $router,
        EventBusInterface $eventBus,
        DomainEventStorage $eventStorage
    ): CommandBusInterface {
        $routerMiddleware = new RouterMiddleware($router);
        $tactician = new LeagueCommandBusFactory([ $routerMiddleware ]);
        $commandBus = new CommandBus($tactician->create());
        $commandBus = new DomainEventDispatcherCommandBusDecorator($commandBus, $eventBus, $eventStorage);

        return $commandBus;
    }

}
