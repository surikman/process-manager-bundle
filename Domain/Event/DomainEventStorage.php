<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Domain\Event;

/**
 *
 */
final class DomainEventStorage
{
    /**
     * @var DomainEventInterface[]
     */
    private $events = [];

    /**
     * @param DomainEventInterface $event
     *
     * @return void
     */
    public function addEvent(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return DomainEventInterface[]
     */
    public function retrieveEventsAndEmpty(): array
    {
        $events = $this->events;
        //  this method may be executed during dispatching event, we need to clear previous events
        $this->events = [];

        return $events;
    }
}
