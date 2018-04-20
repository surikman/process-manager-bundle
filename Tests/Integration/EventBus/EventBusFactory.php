<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\EventBus;

use League\Event\Emitter;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\LeagueEventBus;

/**
 *
 */
final class EventBusFactory
{
    /**
     * @return EventBusInterface
     */
    public static function create(): EventBusInterface
    {
        return new LeagueEventBus(new Emitter());
    }

}
