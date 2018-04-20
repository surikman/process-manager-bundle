<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandHandlerInterface;
use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage;
use SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager\TestCommand3 as TestCommand;

/**
 *
 */
final class TestCommandHandler3 implements CommandHandlerInterface
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
     * Execute command 3 and create event 4...
     *
     * @param TestCommand $command
     *
     * @return void
     */
    public function handle(TestCommand $command): void
    {
        // do something magic in your domain and create Event in your domain

        // this is only for testing without domain...
//        $this->eventStorage->addEvent(new TestEvent1());
        $this->eventStorage->addEvent(new TestEvent4());
    }
}
