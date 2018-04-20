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
final class TestCommand2a extends AbstractCommand
{

    /**
     * @param TestEvent2a $event
     */
    public function __construct(TestEvent2a $event)
    {
        $event->getName();
    }
}
