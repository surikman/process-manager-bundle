<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router;

interface CompilableInterface
{
    /**
     * @return array
     */
    public function compile(): array;
}
