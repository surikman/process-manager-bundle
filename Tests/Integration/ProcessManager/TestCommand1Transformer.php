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
final class TestCommand1Transformer implements CommandTransformerInterface
{

    /**
     * @param EventCollection $events
     *
     * @return CommandInterface
     */
    public function transform(EventCollection $events): CommandInterface
    {
        /** @var TestEvent1 $event1 */
        $event1 = $events->getEvent(TestEvent1::class);

        return new TestCommand1($event1->isWayA());
    }
}
