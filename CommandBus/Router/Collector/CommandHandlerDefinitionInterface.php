<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector;

/**
 *
 */
interface CommandHandlerDefinitionInterface
{
    /**
     * @return string
     */
    public function getServiceId(): string;

    /**
     * @return string
     */
    public function getServiceClass(): string;
}
