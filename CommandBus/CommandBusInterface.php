<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus;

/**
 *
 */
interface CommandBusInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return void
     */
    public function run(CommandInterface $command): void;
}
