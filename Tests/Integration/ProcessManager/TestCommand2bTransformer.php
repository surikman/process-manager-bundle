<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\CommandTransformerInterface;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\EventCollection;

/**
 *
 */
final class TestCommand2bTransformer implements CommandTransformerInterface
{

    /**
     * @param EventCollection $events
     *
     * @return CommandInterface
     */
    public function transform(EventCollection $events): CommandInterface
    {
        return new TestCommand2b($events->getEvent(TestEvent2b::class));
    }
}
