<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandHandlerInterface;
use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage;
use SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager\TestCommand2a as TestCommand;

/**
 *
 */
final class TestCommandHandler2a implements CommandHandlerInterface
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
     * Execute command 2 and create event 3...
     *
     * @param TestCommand $command
     *
     * @return void
     */
    public function handle(TestCommand $command): void
    {
        // do something magic in your domain and create Event in your domain

        // this is only for testing without domain...
        $this->eventStorage->addEvent(new TestEvent3());
    }
}
