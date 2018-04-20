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
final class TestEvent1 extends AbstractEvent implements DomainEventInterface
{
    /**
     * @var bool
     */
    private $wayA;

    /**
     * @param bool $wayA
     */
    public function __construct(bool $wayA = true)
    {
        $this->wayA = $wayA;
    }

    /**
     * @return bool
     */
    public function isWayA(): bool
    {
        return $this->wayA;
    }
}
