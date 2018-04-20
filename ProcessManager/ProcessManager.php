<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

use RuntimeException;
use InvalidArgumentException;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandBusInterface;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\Exception\CircularEventCallException;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\Exception\OrderViolationException;
use SuRiKmAn\ProcessManagerBundle\EventBus\Event\EventInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Handler\EventHandlerInterface;

/**
 *
 */
final class ProcessManager implements ProcessManagerInterface, EventHandlerInterface
{
    /**
     * @var ProcessConfiguration
     */
    private $configuration;

    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    /**
     * @var ProcessCollection
     */
    private $processes;

    /**
     * @var string[]
     */
    private $registeredEvents = [];

    /**
     * @var self|null
     */
    private $parentManager;

    /**
     * @var ProcessFactoryInterface
     */
    private $processFactory;

    /**
     * @param ProcessConfiguration    $configuration
     * @param CommandBusInterface     $commandBus
     * @param ProcessFactoryInterface $processFactory
     * @param self|null               $parentManager
     */
    public function __construct(
        ProcessConfiguration $configuration,
        CommandBusInterface $commandBus,
        ProcessFactoryInterface $processFactory,
        self $parentManager = null
    ) {
        $this->configuration = $configuration;
        $this->commandBus = $commandBus;
        $this->processFactory = $processFactory;
        $this->processes = new ProcessCollection();
        $this->parentManager = $parentManager;
    }


    /**
     * @param EventInterface    $event
     * @param EventBusInterface $eventBus
     *
     * @return void
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws OrderViolationException
     */
    public function handle(EventInterface $event, EventBusInterface $eventBus): void
    {
        $process = $this->findSuitableProcess($event);
        if (!$process && $this->shouldNewProcessBeStarted($event)) {
            $process = $this->createNewProcess($event);
            $this->processes->addProcess($process);
        }

        if ($process === null) {
            return; // skip process...
        }
        $command = $process->getCommand($event);

        if ($process->shouldContinueInSubProcesses()) {
            $this->initSubProcessManagers($eventBus);
        }
        $this->registerNextEvent($process, $eventBus);
        $this->commandBus->run($command);
    }

    /**
     * @param Process           $process
     * @param EventBusInterface $eventBus
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function registerNextEvent(Process $process, EventBusInterface $eventBus): void
    {
        $nextEvent = $process->findNextEvent();
        if ($nextEvent === null) { // no other event
            return;
        }
        $this->registerEvent($eventBus, $nextEvent);
    }

    /**
     * @param EventBusInterface $eventBus
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function initSubProcessManagers(EventBusInterface $eventBus): void
    {
        foreach ($this->configuration->getChildren() as $processConfiguration) {
            $firstEvent = $processConfiguration->getFirstEvent();
            if ($firstEvent === null) {
                throw new InvalidArgumentException('First event of ProcessManager configuration is required');
            }
            $childProcessManager = new self(
                $processConfiguration,
                $this->commandBus,
                $this->processFactory,
                $this
            );
            $childProcessManager->registerEvent($eventBus, $firstEvent);
        }
    }

    /**
     * @param EventBusInterface $eventBus
     * @param string            $event
     *
     * @return void
     */
    private function registerEvent(EventBusInterface $eventBus, string $event): void
    {
        if (isset($this->registeredEvents[$event])) { // already registered
            return;
        }
        $eventBus->addHandler($event, $this, 'handle');
        $this->registeredEvents[$event] = $event;
    }

    /**
     * @param EventInterface $event
     *
     * @return null|Process
     * @throws RuntimeException
     */
    private function findSuitableProcess(EventInterface $event): ?Process
    {
        $process = $this->processes->findByEvent($event);
        if (!$process) {
            return null;
        }

        if ($process->containsEvent($event)) {
            throw new CircularEventCallException();
        }

        return $process;
    }

    /**
     * @param EventInterface $event
     *
     * @return bool
     */
    public function shouldNewProcessBeStarted(EventInterface $event): bool
    {
        // only first event in configuration could start new process
        if (!$this->configuration->isFirst($event->getName())) {
            return false;
        }

        if ($this->parentManager) {
            // only if exists parent process for current event
            return $this->findParentProcess($event) !== null;
        }

        // when it is first event, and it is root manager, new starting of process is allowed
        return true;
    }

    /**
     * @param EventInterface $event
     *
     * @return null|Process
     */
    private function findParentProcess(EventInterface $event): ?Process
    {
        if ($this->parentManager) {
            return $this->parentManager->processes->findByEvent($event);
        }

        return null;
    }

    /**
     * @param EventInterface $event
     *
     * @return Process
     * @throws RuntimeException
     */
    private function createNewProcess(EventInterface $event): Process
    {
        // when this PM is as root manager, create new process immediately
        if (!$this->parentManager) {
            return $this->processFactory->create($this->configuration);
        }

        $parentProcess = $this->findParentProcess($event);

        if (!$parentProcess) {
            throw new RuntimeException('Unable to fetch parent process.');
        }

        if (!$parentProcess->shouldContinueInSubProcesses()) {
            throw new RuntimeException('Unable to create sub process for unfinished parent process.');
        }

        return $this->processFactory->create($this->configuration, $parentProcess);
    }
}
