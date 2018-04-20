<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route;

use SuRiKmAn\ProcessManagerBundle\Exception\CommandNotFound;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector\CommandHandlerDefinitionInterface;

/**
 *
 */
interface RouteFactoryInterface
{
    /**
     * @param CommandHandlerDefinitionInterface $commandHandlerDefinition
     *
     * @return Route
     * @throws CommandNotFound
     */
    public function create(CommandHandlerDefinitionInterface $commandHandlerDefinition): Route;
}
