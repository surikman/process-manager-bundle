<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandHandlerInterface;
use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage;
use SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager\TestCommand1 as TestCommand;

/**
 *
 */
final class TestCommandHandler1 implements CommandHandlerInterface
{
    /**
     * @var DomainEventStorage
     */
    private $eventStorage;

    /**
     * @param DomainEventStorage $eventStorage
     */
    public function __construct(DomainEventStorage $eventStorage)
    {
        $this->eventStorage = $eventStorage;
    }

    /**
     * Execute command 1 and create event 2...
     *
     * @param TestCommand $command
     *
     * @return void
     */
    public function handle(TestCommand $command): void
    {
        // do something magic in your domain and create Event in your domain

//        $nextEvent = $command->isWayA() ? new TestEvent2a() : new TestEvent2b();
//        $this->eventStorage->addEvent($nextEvent);

        // this is only for testing without domain...

        // spustit obe casti...
        $this->eventStorage->addEvent(new TestEvent2a());
        $this->eventStorage->addEvent(new TestEvent2b());
    }
}
