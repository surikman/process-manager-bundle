<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus;

use SuRiKmAn\ProcessManagerBundle\EventBus\Event\PostCommand;
use SuRiKmAn\ProcessManagerBundle\EventBus\Event\PreCommand;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;

/**
 *
 */
final class EventTriggeringCommandBus implements CommandBusInterface
{
    /**
     * @var CommandBusInterface
     */
    private $delegate;

    /**
     * @var EventBusInterface
     */
    private $eventBus;

    /**
     * @param CommandBusInterface $delegate
     * @param EventBusInterface   $eventBus
     */
    public function __construct(CommandBusInterface $delegate, EventBusInterface $eventBus)
    {
        $this->delegate = $delegate;
        $this->eventBus = $eventBus;
    }

    /**
     * @param CommandInterface $command
     *
     * @return void
     */
    public function run(CommandInterface $command): void
    {
        $this->eventBus->dispatch(new PreCommand($command));
        $this->delegate->run($command);
        $this->eventBus->dispatch(new PostCommand($command));
    }
}
