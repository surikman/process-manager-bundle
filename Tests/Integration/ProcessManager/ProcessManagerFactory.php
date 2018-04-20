<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use Ramsey\Uuid\UuidFactory;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandBusInterface;
use SuRiKmAn\ProcessManagerBundle\EventBus\EventBusInterface;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\Generator\UuidProcessIdGenerator;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessFactory;
use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessManager;

/**
 *
 */
final class ProcessManagerFactory
{
    /**
     * @param array               $configuration
     * @param EventBusInterface   $eventBus
     * @param CommandBusInterface $commandBus
     *
     * @return ProcessManager
     */
    public static function create(
        array $configuration,
        EventBusInterface $eventBus,
        CommandBusInterface $commandBus
    ): ProcessManager {
        $processFactory = new ProcessFactory(new UuidProcessIdGenerator(new UuidFactory()));
        $processConfiguration = ProcessConfigurationFactory::create($configuration);


        $processManager = new ProcessManager($processConfiguration, $commandBus, $processFactory);
        $eventBus->addHandler($processConfiguration->getFirstEvent(), $processManager, 'handle');

        return $processManager;
    }

}
