<?php

/** @codingStandardsIgnoreStart */

require_once '../../vendor/autoload.php';

use SuRiKmAn\ProcessManagerBundle\Tests\Integration;

$configuration = [
    'main' => [
        Integration\ProcessManager\TestEvent1::class => Integration\ProcessManager\TestCommand1Transformer::class,
    ],
    'sub'  => [
        [
            'main' => [
                Integration\ProcessManager\TestEvent2a::class => Integration\ProcessManager\TestCommand2aTransformer::class,
            ],
            'sub'  => [
                [
                    'main' => [
                        Integration\ProcessManager\TestEvent3::class => Integration\ProcessManager\TestCommand3Transformer::class,
                    ],
                ],
            ],
        ],
        [
            'main' => [
                Integration\ProcessManager\TestEvent2b::class => Integration\ProcessManager\TestCommand2bTransformer::class,
            ],
            'sub'  => [
                [
                    'main' => [
                        Integration\ProcessManager\TestEvent3::class => Integration\ProcessManager\TestCommand3Transformer::class,
                        Integration\ProcessManager\TestEvent4::class => Integration\ProcessManager\TestCommand4Transformer::class,
                    ],
                ],
            ],
        ],
    ],
];
$routes = [
    Integration\ProcessManager\TestCommand1::class  => Integration\ProcessManager\TestCommandHandler1::class,
    Integration\ProcessManager\TestCommand2a::class => Integration\ProcessManager\TestCommandHandler2a::class,
    Integration\ProcessManager\TestCommand2b::class => Integration\ProcessManager\TestCommandHandler2b::class,
    Integration\ProcessManager\TestCommand3::class  => Integration\ProcessManager\TestCommandHandler3::class,
    Integration\ProcessManager\TestCommand4::class  => Integration\ProcessManager\TestCommandHandler4::class,
];

$eventStorage = new \SuRiKmAn\ProcessManagerBundle\Domain\Event\DomainEventStorage();
$eventBus = Integration\EventBus\EventBusFactory::create();
$router = Integration\CommandBus\RouterFactory::create($routes, $eventStorage);
$commandBus = Integration\CommandBus\CommandBusFactory::create($router, $eventBus, $eventStorage);
$processManager = Integration\ProcessManager\ProcessManagerFactory::create($configuration, $eventBus, $commandBus);

$start = round(microtime(true) * 1000);
$eventBus->dispatch(new Integration\ProcessManager\TestEvent1(true));
$end = round(microtime(true) * 1000);
printf('Took %s msec', $end - $start);
echo "\n";
die;
/** @codingStandardsIgnoreEnd */