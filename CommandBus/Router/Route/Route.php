<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Route;

use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector\CommandHandlerDefinitionInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\CompilableInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Metadata\CommandClassMetadata;
use SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Metadata\CommandHandlerClassMetadata;

final class Route implements CompilableInterface, RouteInterface
{
    /**
     * @var string
     */
    private $commandName;

    /**
     * @var string
     */
    private $commandClass;

    /**
     * @var string
     */
    private $handlerId;

    /**
     * @var string
     */
    private $executor;

    /**
     * @param string $commandName
     * @param string $commandClass
     * @param string $handlerId
     * @param string $executor
     */
    public function __construct(string $commandName, string $commandClass, string $handlerId, string $executor)
    {
        $this->commandName = $commandName;
        $this->commandClass = $commandClass;
        $this->handlerId = $handlerId;
        $this->executor = $executor;
    }

    /**
     * @param CommandHandlerDefinitionInterface $definition
     * @param CommandClassMetadata              $commandClassMetadata
     * @param CommandHandlerClassMetadata       $commandHandlerClassMetadata
     *
     * @return Route
     */
    public static function createByMetadata(
        CommandHandlerDefinitionInterface $definition,
        CommandHandlerClassMetadata $commandHandlerClassMetadata,
        CommandClassMetadata $commandClassMetadata
    ): Route {
        return new self(
            $commandClassMetadata->getName(),
            $commandHandlerClassMetadata->getCommandClass(),
            $definition->getServiceId(),
            $commandHandlerClassMetadata->getExecutorName()
        );
    }

    /**
     * @param array $cache
     *
     * @return Route
     */
    public static function createFromCache(array $cache): Route
    {
        return new self(
            $cache['command_name'],
            $cache['command_class'],
            $cache['handler'],
            $cache['executor']
        );
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * @return string
     */
    public function getCommandClass(): string
    {
        return $this->commandClass;
    }

    /**
     * @return string
     */
    public function getHandlerId(): string
    {
        return $this->handlerId;
    }

    /**
     * @return string
     */
    public function getExecutor(): string
    {
        return $this->executor;
    }

    /**
     * @return array
     */
    public function compile(): array
    {
        return [
            'command_name'  => $this->commandName,
            'command_class' => $this->commandClass,
            'handler'       => $this->handlerId,
            'executor'      => $this->executor,
        ];
    }
}
