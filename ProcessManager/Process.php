<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\Exception\OrderViolationException;
use SuRiKmAn\ProcessManagerBundle\EventBus\Event\EventInterface;

/**
 *
 */
final class Process
{
    /**
     * @var int
     */
    private $currentIndex = 0;

    /**
     * @var ProcessId
     */
    private $pid;

    /**
     * @var ProcessConfiguration
     */
    private $configuration;

    /**
     * @var EventCollection
     */
    private $events;

    /**
     * @param ProcessId            $pid
     * @param ProcessConfiguration $configuration
     */
    public function __construct(
        ProcessId $pid,
        ProcessConfiguration $configuration
    ) {
        $this->pid = $pid;
        $this->configuration = $configuration;
        $this->events = new EventCollection();
    }

    /**
     * @return ProcessId
     */
    public function getPid(): ProcessId
    {
        return $this->pid;
    }

    /**
     * @param EventInterface $event
     *
     * @return bool
     */
    public function containsEvent(EventInterface $event): bool
    {
        return $this->events->contains($event);
    }

    /**
     * @param ProcessId            $processId
     * @param ProcessConfiguration $processConfiguration
     *
     * @return Process
     */
    public function fork(
        ProcessId $processId,
        ProcessConfiguration $processConfiguration
    ): self {
        $processId = $this->generateChildPid($processId);
        $process = new self($processId, $processConfiguration);
        $process->events = clone $this->events;

        return $process;
    }

    /**
     * @param EventInterface $event
     *
     * @return CommandInterface
     * @throws OrderViolationException
     */
    public function getCommand(EventInterface $event): CommandInterface
    {
        $this->validate($event->getName());
        $this->events->addEvent($event);
        $commandTransformer = $this->configuration->getCommandTransformer($this->currentIndex++);
        $command = $commandTransformer->transform($this->events);
        $command->getMetadata()->append($event->getAllMetadata());
        $command->getMetadata()->add($this->pid);

        return $command;
    }

    /**
     * @return bool
     */
    public function shouldContinueInSubProcesses(): bool
    {
        if (!$this->configuration->hasChildren()) {
            return false;
        }

        return $this->findNextEvent() === null;
    }

    /**
     * @param ProcessId $processId
     *
     * @return ProcessId
     */
    private function generateChildPid(ProcessId $processId): ProcessId
    {
        return $this->pid->newChild($processId);
    }

    /**
     * @param string $eventName
     *
     * @return void
     * @throws OrderViolationException
     */
    private function validate(string $eventName): void
    {
        $expectedEventName = $this->findNextEvent();
        if (null === $expectedEventName) {
            throw new OrderViolationException(
                sprintf(
                    'Order violation - unexpected event %s (no other event in configuration)',
                    $eventName
                )
            );
        }

        if ($expectedEventName !== $eventName) {
            throw new OrderViolationException(
                sprintf(
                    'Order violation - expected "%s", but got %s event',
                    $expectedEventName,
                    $eventName
                )
            );
        }
    }

    /**
     * @return null|string
     */
    public function findNextEvent(): ?string
    {
        return $this->configuration->findEvent($this->currentIndex);
    }
}
