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
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        if ($this->metadata === null) {
            $this->metadata = new Metadata();
        }

        return $this->metadata;
    }
}
