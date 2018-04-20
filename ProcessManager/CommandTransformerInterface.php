<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;

/**
 *
 */
interface CommandTransformerInterface
{
    /**
     * @param EventCollection $events
     *
     * @return CommandInterface
     */
    public function transform(EventCollection $events): CommandInterface;
}
