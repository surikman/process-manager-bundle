<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Domain\Event;

use SuRiKmAn\ProcessManagerBundle\EventBus\Event\AbstractEvent;

/**
 *
 */
class AbstractDomainEvent extends AbstractEvent implements DomainEventInterface
{
}
