<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route\Route;

interface RegistryInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return Route
     */
    public function getRouteByCommand(CommandInterface $command): Route;

    /**
     * @param string $commandName
     *
     * @return Route
     */
    public function getRouteByCommandName(string $commandName): Route;
}
