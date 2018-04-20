<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus;

use SuRiKmAn\ProcessManagerBundle\CommandBus\Metadata\Metadata;

/**
 *
 */
interface CommandInterface
{
    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata;
}
