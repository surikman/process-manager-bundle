<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector;

/**
 *
 */
final class CommandHandlerDefinition implements CommandHandlerDefinitionInterface
{
    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var string
     */
    private $serviceClass;

    /**
     * @param string $serviceId
     * @param string $serviceClass
     */
    public function __construct(string $serviceId, string $serviceClass)
    {
        $this->serviceId = $serviceId;
        $this->serviceClass = $serviceClass;
    }

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * @return string
     */
    public function getServiceClass(): string
    {
        return $this->serviceClass;
    }
}
