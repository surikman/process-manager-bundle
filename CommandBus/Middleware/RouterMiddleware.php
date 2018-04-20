<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Middleware;

use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RouterInterface;

/**
 *
 */
class RouterMiddleware implements MiddlewareInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param CommandInterface $command
     * @param callable         $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $this->router->match($command)();

        return $next($command);
    }
}
