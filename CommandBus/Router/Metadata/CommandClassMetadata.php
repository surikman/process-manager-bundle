<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Metadata;

use Metadata\MergeableClassMetadata as BaseClassMetadata;

final class CommandClassMetadata extends BaseClassMetadata
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
