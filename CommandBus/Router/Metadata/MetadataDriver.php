<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Metadata;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use InvalidArgumentException;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandHandlerInterface;
use SuRiKmAn\ProcessManagerBundle\CommandBus\CommandInterface;

/**
 *
 */
final class MetadataDriver implements DriverInterface
{
    /**
     * @param ReflectionClass $class
     *
     * @return ClassMetadata
     * @throws InvalidArgumentException
     */
    public function loadMetadataForClass(ReflectionClass $class)
    {
        if ($this->isCommandHandler($class)) {
            return $this->createCommandHandlerMetadata($class);
        }

        if ($this->isCommand($class)) {
            return $this->createCommandMetadata($class);
        }

        return null;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return bool
     */
    private function isCommandHandler(ReflectionClass $class): bool
    {
        return $class->implementsInterface(CommandHandlerInterface::class) && !$class->isAbstract();
    }

    /**
     * @param ReflectionClass $class
     *
     * @return bool
     */
    private function isCommand(ReflectionClass $class): bool
    {
        return $class->implementsInterface(CommandInterface::class) && !$class->isAbstract();
    }

    /**
     * @param ReflectionClass $class
     *
     * @return CommandHandlerClassMetadata
     * @throws InvalidArgumentException
     */
    private function createCommandHandlerMetadata(ReflectionClass $class): CommandHandlerClassMetadata
    {
        $name = $class->getName();
        [ $executor, $commandClass ] = $this->getCommandClassAndExecutor($class);
        if (null === $executor) {
            throw new InvalidArgumentException(
                'Please write only One method with type hinted 
                CommandInterface as parameter in CommandHandler(' . $name . ')'
            );
        }
        if (null === $commandClass) {
            throw new InvalidArgumentException(
                'Please use a valid command class for the executor method in CommandHandler(' . $name . ')'
            );
        }

        return new CommandHandlerClassMetadata($name, $executor, $commandClass);
    }

    /**
     * @param ReflectionClass $class
     *
     * @return array
     */
    private function getCommandClassAndExecutor(ReflectionClass $class): array
    {
        $commandClasses = [];
        $name = $class->getName();
        foreach ($class->getMethods() as $method) {
            if ($method->class !== $name) {
                continue;
            }

            $commandClass = $this->getCommandClassFromMethod($method);
            if (null !== $commandClass) {
                $commandClasses[$method->getName()] = $commandClass;
            }
        }
        if (count($commandClasses) === 1) { // only one executor is allowed
            $executor = key($commandClasses);

            return [ $executor, $commandClasses[$executor] ];
        }

        return [ null, null ];
    }

    /**
     * @param ReflectionMethod $method
     *
     * @return null|string
     */
    private function getCommandClassFromMethod(ReflectionMethod $method): ?string
    {
        $parameters = $method->getParameters();
        $first = reset($parameters);
        if (!($first instanceof ReflectionParameter)) {
            return null;
        }
        $inputClass = $first->getClass();
        if ($method->isPublic() && $inputClass->implementsInterface(CommandInterface::class)) {
            return $inputClass->getName();
        }

        return null;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return CommandClassMetadata
     * @throws InvalidArgumentException
     */
    private function createCommandMetadata(ReflectionClass $class): CommandClassMetadata
    {
        return new CommandClassMetadata($class->getName());
    }
}
