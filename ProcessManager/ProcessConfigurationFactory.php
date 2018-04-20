<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

/**
 *
 */
final class ProcessConfigurationFactory
{
    /**
     * @param array $events
     * @param array $subProcesses
     *
     * @return ProcessConfiguration
     */
    public function create(array $events, array $subProcesses): ProcessConfiguration
    {
        return new ProcessConfiguration($events, $subProcesses);
    }
}
