<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Tests\Integration\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessConfiguration;

/**
 *
 */
final class ProcessConfigurationFactory
{

    /**
     * @param array $configuration
     *
     * @return ProcessConfiguration
     */
    public static function create(array $configuration): ProcessConfiguration
    {
        return new ProcessConfiguration(...self::createProcessConfiguration($configuration));
    }

    /**
     * @param array $configuration
     *
     * @return array
     */
    private static function createProcessConfiguration(array $configuration): array
    {
        $events = [];
        $subProcesses = [];
        $main = $configuration['main'] ?? [];
        $sub = $configuration['sub'] ?? [];

        foreach ($main as $event => $commandTransformer) {
            $events[$event] = new $commandTransformer();
        }
        foreach ($sub as $subTypeConfiguration) {
            $subProcesses[] = self::createProcessConfiguration($subTypeConfiguration);
        }

        return [ $events, $subProcesses ];
    }
}
