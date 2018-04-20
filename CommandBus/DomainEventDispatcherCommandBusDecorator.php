<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus;

use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;

/**
 *
 */
final class DomainEventDispatcherCommandBusDecorator implements CommandBusInterface
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
     * @var DomainEventStorage
     */
    private $eventStorage;

    /**
     * @param CommandBusInterface $delegate
     * @param EventBusInterface   $eventBus
     * @param DomainEventStorage  $eventStorage
     */
    public function __construct(
        CommandBusInterface $delegate,
        EventBusInterface $eventBus,
        DomainEventStorage $eventStorage
    ) {
        $this->delegate = $delegate;
        $this->eventBus = $eventBus;
        $this->eventStorage = $eventStorage;
    }

    /**
     * @param CommandInterface $command
     *
     * @return void
     */
    public function run(CommandInterface $command): void
    {
        $metadata = $command->getMetadata()->getAll();
        $this->delegate->run($command);
        $this->dispatchDomainEvents($metadata);
    }

    /**
     * @param array $commandMetadata
     *
     * @return void
     */
    private function dispatchDomainEvents(array $commandMetadata): void
    {
        foreach ($this->eventStorage->retrieveEventsAndEmpty() as $event) {
            $event->appendMetadata($commandMetadata);
            $this->eventBus->dispatch($event);
        }
    }
}
