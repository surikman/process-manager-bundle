<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus;

use League\Tactician\CommandBus as LeagueCommandBus;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Middleware\MiddlewareAdapter;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Middleware\MiddlewareInterface;

/**
 *
 */
final class LeagueCommandBusFactory
{
    /**
     * @var array|MiddlewareInterface[]
     */
    private $services;

    /**
     * @param array|MiddlewareInterface[] $services
     */
    public function __construct($services)
    {
        $this->services = $services;
    }


    /**
     * @return LeagueCommandBus
     */
    public function create(): LeagueCommandBus
    {
        return new LeagueCommandBus($this->createMiddleware($this->services));
    }

    /**
     * @param array|MiddlewareInterface[] $middlewareServices
     *
     * @return array
     */
    private function createMiddleware(array $middlewareServices): array
    {
        return array_map(function (MiddlewareInterface $middleware) {
            return new MiddlewareAdapter($middleware);
        }, $middlewareServices);
    }
}
