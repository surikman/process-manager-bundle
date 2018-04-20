<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

/**
 *
 */
interface ProcessFactoryInterface
{
    /***
     * @param ProcessConfiguration $processConfiguration
     * @param Process|null         $parentProcess
     *
     * @return Process
     */
    public function create(
        ProcessConfiguration $processConfiguration,
        Process $parentProcess = null
    ): Process;
}
