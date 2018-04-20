<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route;

interface RouteInterface
{
    /**
     * @return string
     */
    public function getCommandName(): string;

    /**
     * @return string
     */
    public function getCommandClass(): string;

    /**
     * @return string
     */
    public function getHandlerId(): string;

    /**
     * @return string
     */
    public function getExecutor(): string;
}