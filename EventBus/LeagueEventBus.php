<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\EventBus;

use League\Event\EmitterInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Event\EventInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Handler\EventHandlerInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Handler\SubscribedEventHandlerInterface;

/**
 *
 */
final class LeagueEventBus implements EventBusInterface
{
    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @param EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function dispatch(EventInterface $event): void
    {
        $this->emitter->emit($event);
    }

    /**
     * @inheritdoc
     */
    public function addHandler(
        string $eventClass,
        EventHandlerInterface $handler,
        string $handlerMethod,
        int $priority = 0
    ): void {
        $listener = function ($event) use ($handler, $handlerMethod) {
            if ($event instanceof EventInterface) { // handle only our own Events
                return $handler->$handlerMethod($event, $this);
            }
        };
        $this->emitter->addListener($eventClass, $listener, $priority);
    }

    /**
     * @inheritdoc
     */
    public function addSubscribedHandler(SubscribedEventHandlerInterface $handler): void
    {
        foreach ($handler->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->addHandler($eventName, $handler, $params);
            } elseif (is_string($params[0])) {
                $this->addHandler($eventName, $handler, $params[0], $params[1] ?? 0);
            } else {
                foreach ($params ?: [] as $listener) {
                    $this->addHandler($eventName, $handler, $listener, $listener[1] ?? 0);
                }
            }
        }
    }
}
