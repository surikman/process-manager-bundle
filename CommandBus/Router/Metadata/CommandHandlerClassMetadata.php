<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Metadata;

use Metadata\MergeableClassMetadata as BaseClassMetadata;

/**
 *
 */
final class CommandHandlerClassMetadata extends BaseClassMetadata
{

    /**
     * @var string
     */
    private $executorName;

    /**
     * @var string
     */
    private $commandClass;

    /**
     * @param string $name
     * @param string $executor
     *
     * @param string $commandClass
     */
    public function __construct(string $name, string $executor, string $commandClass)
    {
        parent::__construct($name);
        $this->executorName = $executor;
        $this->commandClass = $commandClass;
    }


    /**
     * @return string
     */
    public function getExecutorName(): string
    {
        return $this->executorName;
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
    public function getName(): string
    {
        return $this->name;
    }
}
