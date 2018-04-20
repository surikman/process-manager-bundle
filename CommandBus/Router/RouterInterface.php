<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;

interface RouterInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return callable
     */
    public function match(CommandInterface $command): callable;
}
