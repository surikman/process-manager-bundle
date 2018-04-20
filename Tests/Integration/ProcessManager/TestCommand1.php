<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\CommandBus\AbstractCommand;

/**
 *
 */
final class TestCommand1 extends AbstractCommand
{
    /**
     * @var bool
     */
    private $wayA;

    /**
     * @param bool $wayA
     */
    public function __construct(bool $wayA)
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
