<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route;

use Metadata\MetadataFactoryInterface;
use SuRiKmAn\ProcessManagerBundle\Exception\CommandNotFound;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector\CommandHandlerDefinitionInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Metadata\CommandClassMetadata;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Metadata\CommandHandlerClassMetadata;

/**
 *
 */
final class RouteFactory implements RouteFactoryInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadata;

    /**
     * @param MetadataFactoryInterface $metadata
     */
    public function __construct(MetadataFactoryInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param CommandHandlerDefinitionInterface $commandHandlerDefinition
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return Route
     * @throws CommandNotFound
     */
    public function create(CommandHandlerDefinitionInterface $commandHandlerDefinition): Route
    {
        /** @var CommandHandlerClassMetadata|null $commandHandlerMetadata */
        $commandHandlerMetadata = $this->metadata->getMetadataForClass($commandHandlerDefinition->getServiceClass());
        if (null === $commandHandlerMetadata) {
            throw new CommandNotFound();
        }


        /** @var CommandClassMetadata|null $commandMetadata */
        $commandMetadata = $this->metadata->getMetadataForClass($commandHandlerMetadata->getCommandClass());
        if (null === $commandMetadata) {
            throw new CommandNotFound();
        }


        return Route::createByMetadata($commandHandlerDefinition, $commandHandlerMetadata, $commandMetadata);
    }
}
