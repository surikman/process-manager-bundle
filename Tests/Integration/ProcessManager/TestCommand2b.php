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
final class TestCommand2b extends AbstractCommand
{

    /**
     * @param TestEvent2b $event
     */
    public function __construct(TestEvent2b $event)
    {
        $event->getName();
    }
}
