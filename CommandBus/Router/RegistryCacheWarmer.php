<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

/**
 *
 */
final class RegistryCacheWarmer extends CacheWarmer
{
    public const WARMED_FILE_NAME = 'surikman/command_bus_registry';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * @inheritdoc
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function warmUp($cacheDir)
    {
        $this->createCacheDirectory($cacheDir);
        $routes = $this->registry->compile();
        $this->writeCacheFile(
            $this->getCacheFile($cacheDir),
            sprintf("<?php return %s;\n", var_export($routes, true))
        );
    }

    /**
     * @param string $cacheDir
     *
     * @return string
     */
    private function getCacheFile(string $cacheDir): string
    {
        return sprintf('%s/%s.php', $cacheDir, self::WARMED_FILE_NAME);
    }

    /**
     * @param string $cacheDir
     *
     * @return void
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    private function createCacheDirectory(string $cacheDir): void
    {
        $cacheFile = $this->getCacheFile($cacheDir);
        $cacheDir = dirname($cacheFile);
        if (is_dir($cacheDir)) {
            return;
        }
        if (!mkdir($cacheDir) && !is_dir($cacheDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $cacheDir));
        }
    }
}
