<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus;

use Psr\Log\LoggerInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RegistryInterface;

/**
 *
 */
final class LoggingCommandBus implements CommandBusInterface
{
    /**
     * @var CommandBusInterface
     */
    private $delegate;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Levels from Psr\Log\LogLevel
     *
     * @var string
     */
    private $level;

    /**
     * @var RegistryInterface
     */
    private $registry;


    /**
     * @param CommandBusInterface $delegate
     * @param RegistryInterface   $registry
     * @param LoggerInterface     $logger
     * @param string              $level
     */
    public function __construct(
        CommandBusInterface $delegate,
        RegistryInterface $registry,
        LoggerInterface $logger,
        string $level
    ) {
        $this->delegate = $delegate;
        $this->logger = $logger;
        $this->level = $level;
        $this->registry = $registry;
    }


    /**
     * @param CommandInterface $command
     *
     * @return void
     */
    public function run(CommandInterface $command): void
    {
        $commandType = $this->registry->getRouteByCommand($command)->getCommandName();
        $this->logger->log(
            $this->level,
            sprintf('Start Command - %s', $commandType),
            [ 'command' => $command ]
        );
        $this->delegate->run($command);
        $this->logger->log(
            $this->level,
            sprintf('Finish Command - %s', $commandType)
        );
    }
}
