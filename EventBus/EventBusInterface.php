<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\EventBus;

use SuRiKmAn\ProcessManagerBundle\EventBus\Event\EventInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Handler\EventHandlerInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Handler\SubscribedEventHandlerInterface;

/**
 *
 */
interface EventBusInterface
{
    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function dispatch(EventInterface $event): void;

    /**
     * @param string                $eventClass
     * @param EventHandlerInterface $handler
     * @param string                $handlerMethod
     * @param int                   $priority
     *
     * @return void
     */
    public function addHandler(
        string $eventClass,
        EventHandlerInterface $handler,
        string $handlerMethod,
        int $priority = 0
    ): void;

    /**
     * @param SubscribedEventHandlerInterface $handler
     *
     * @return void
     */
    public function addSubscribedHandler(SubscribedEventHandlerInterface $handler): void;
}
