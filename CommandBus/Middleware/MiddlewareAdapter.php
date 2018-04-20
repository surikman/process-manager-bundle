<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Middleware;

use League\Tactician\Middleware;

/**
 *
 */
final class MiddlewareAdapter implements Middleware
{

    /**
     * @var MiddlewareInterface
     */
    private $middleware;

    /**
     * @param MiddlewareInterface $middleware
     */
    public function __construct(MiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        return $this->middleware->execute($command, $next);
    }
}
