<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

use InvalidArgumentException;
use SuRiKmAn\ProcessManagerBundle\EventBus\Event\EventInterface;

/**
 *
 */
final class EventCollection
{
    private $events = [];

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function addEvent(EventInterface $event): void
    {
        $this->events[get_class($event)] = $event;
    }

    /**
     * @param string $eventClass
     *
     * @return EventInterface
     * @throws InvalidArgumentException
     */
    public function getEvent(string $eventClass): EventInterface
    {
        $event = $this->events[$eventClass] ?? null;
        if ($event === null) {
            throw new InvalidArgumentException(sprintf('No EventClass %s found', $eventClass));
        }

        return $event;
    }

    /**
     * @param EventInterface $event
     *
     * @return bool
     */
    public function contains(EventInterface $event): bool
    {
        return isset($this->events[get_class($event)]);
    }
}
