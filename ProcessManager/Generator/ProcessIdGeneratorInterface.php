<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager\Generator;

use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessId;

/**
 *
 */
interface ProcessIdGeneratorInterface
{
    /**
     * @return ProcessId
     */
    public function generate(): ProcessId;
}
