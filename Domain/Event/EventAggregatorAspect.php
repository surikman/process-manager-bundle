<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Domain\Event;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;

/**
 *
 */
final class EventAggregatorAspect implements Aspect
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
     * @After("
    within(SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventInterface+)
    && execution(public **->__construct(*))
    ")
     * @param MethodInvocation $invocation
     */
    public function onCreateEvent(MethodInvocation $invocation): void
    {
        /** @var DomainEventInterface $event */
        $event = $invocation->getThis();
        $this->eventStorage->addEvent($event);
    }
}
