<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\EventBus\Event\EventInterface;

/**
 *
 */
final class ProcessCollection
{
    /**
     * @var Process[]
     */
    private $processes = [];

    /**
     * @param Process $process
     *
     * @return void
     */
    public function addProcess(Process $process): void
    {
        $this->processes[$process->getPid()->getId()] = $process;
    }

    /**
     * @param ProcessId $processId
     *
     * @return null|Process
     */
    public function find(ProcessId $processId): ?Process
    {
        return $this->processes[$processId->getId()] ?? null;
    }

    /**
     * @param EventInterface $event
     *
     * @return null|Process
     */
    public function findByEvent(EventInterface $event): ?Process
    {
        if (!$event->hasMetadata(ProcessId::class)) {
            return null;
        }
        /** @var ProcessId $processId */
        $processId = $event->getMetadata(ProcessId::class);

        return $this->find($processId);
    }
}
