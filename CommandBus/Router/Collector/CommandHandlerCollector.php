<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Router\Collector;

use ArrayIterator;
use IteratorAggregate;

/**
 *
 */
final class CommandHandlerCollector implements IteratorAggregate
{
    /**
     * @var array|string[]
     */
    private $handlerServices = [];

    /**
     * @param array|CommandHandlerDefinitionInterface[] $services
     */
    public function __construct(array $services)
    {
        foreach ($services as $service) {
            $this->addHandler($service);
        }
    }

    /**
     * @param CommandHandlerDefinitionInterface $handlerService
     *
     * @return void
     */
    private function addHandler(CommandHandlerDefinitionInterface $handlerService): void
    {
        $this->handlerServices[$handlerService->getServiceId()] = $handlerService;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->handlerServices);
    }
}
