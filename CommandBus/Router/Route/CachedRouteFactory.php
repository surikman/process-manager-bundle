<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route;

use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector\CommandHandlerDefinitionInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RegistryCacheWarmer;

/**
 *
 */
final class CachedRouteFactory implements RouteFactoryInterface
{
    /**
     * @var RouteFactoryInterface
     */
    private $delegate;

    /**
     * @var array
     */
    private $cache;

    /**
     * @var array
     */
    private $cacheHit;

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param RouteFactoryInterface $delegate
     * @param string|null           $cacheDir
     * @param bool                  $isDebug
     */
    public function __construct(RouteFactoryInterface $delegate, string $cacheDir = null, bool $isDebug = false)
    {
        $this->delegate = $delegate;
        $cacheFile = sprintf('%s/%s.php', $cacheDir, RegistryCacheWarmer::WARMED_FILE_NAME);
        if (!$isDebug && null !== $cacheDir && file_exists($cacheFile)) {
            $this->cache = require $cacheFile;
        }
    }


    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param CommandHandlerDefinitionInterface $commandHandlerDefinition
     *
     * @return Route
     * @throws \SuRiKmAn\ProcessManagerBundle\Exception\CommandNotFound
     */
    public function create(CommandHandlerDefinitionInterface $commandHandlerDefinition): Route
    {
        $handlerId = $commandHandlerDefinition->getServiceId();
        // local cache
        if (isset($this->cacheHit[$handlerId])) {
            return $this->cacheHit[$handlerId];
        }

        if (isset($this->cache[$handlerId])) {
            return $this->cacheHit[$handlerId] = Route::createFromCache($this->cache[$handlerId]);
        }

        return $this->cacheHit[$handlerId] = $this->delegate->create($commandHandlerDefinition);
    }
}
