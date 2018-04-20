<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

/**
 *
 */
final class ProcessConfiguration
{
    /**
     * @var string[]
     */
    private $events;

    /**
     * @var CommandTransformerInterface[]
     */
    private $commands;

    /**
     * @var ProcessConfiguration[]
     */
    private $children;

    /**
     * @param array $events
     * @param array $subProcesses
     */
    public function __construct(array $events, array $subProcesses)
    {
        $this->events = array_keys($events);
        $this->commands = array_values($events);
        $this->configureChildren($subProcesses);
    }

    /**
     * @param array $subProcesses
     *
     * @return void
     */
    private function configureChildren(array $subProcesses): void
    {
        foreach ($subProcesses as $subProcess) {
            $events = $subProcess[0] ?? [];
            $nextSubProcesses = $subProcess[1] ?? [];
            $this->children[] = new self($events, $nextSubProcesses);
        }
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return $this->children !== null;
    }

    /**
     * @return ProcessConfiguration[]
     */
    public function getChildren(): array
    {
        if ($this->hasChildren()) {
            return $this->children;
        }

        return [];
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function isFirst(string $eventName): bool
    {
        return $this->getFirstEvent() === $eventName;
    }

    /**
     * @param int $index
     *
     * @return CommandTransformerInterface
     */
    public function getCommandTransformer(int $index): CommandTransformerInterface
    {
        return $this->commands[$index];
    }

    /**
     * @param int $index
     *
     * @return null|string
     */
    public function findEvent(int $index): ?string
    {
        return $this->events[$index] ?? null;
    }

    /**
     * @return null|string
     */
    public function getFirstEvent(): ?string
    {
        return $this->events[0] ?? null;
    }
}
