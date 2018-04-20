<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\EventBus\Event\EventInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;

/**
 *
 */
interface ProcessManagerInterface
{
    /**
     * @param EventInterface    $event
     * @param EventBusInterface $eventBus
     *
     * @return void
     */
    public function handle(EventInterface $event, EventBusInterface $eventBus): void;
}
