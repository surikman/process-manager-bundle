<?php

namespace SuRiKmAn\ProcessManagerBundle\CommandBus;

use League\Tactician\CommandBus as LeagueCommandBus;

/**
 *
 */
final class CommandBus implements CommandBusInterface
{
    /**
     * @var LeagueCommandBus
     */
    private $commandBus;

    /**
     * @param LeagueCommandBus $commandBus
     */
    public function __construct(LeagueCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param CommandInterface $command
     *
     * @return void
     */
    public function run(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}
