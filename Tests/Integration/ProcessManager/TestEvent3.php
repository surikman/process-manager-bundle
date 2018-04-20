<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\Event\AbstractEvent;

/**
 *
 */
final class TestEvent3 extends AbstractEvent implements DomainEventInterface
{
    public function __construct()
    {

    }
}
