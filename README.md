ProcessManager Symfony Bundle
======

```bash
$ composer require surikman/process-manager-bundle
```


## Bundle

```php
// config/bundles.php add
SuRiKmAn\ProcessManagerBundle\SuRiKmAnProcessManagerBundle::class => ['all' => true], 

// and this as first bundle !!!
Go\Symfony\GoAopBundle\GoAopBundle::class => ['all' => true],

```

# Configuration

```yaml
# config/packages/surikman_process_manager.yaml
surikman_process_manager:
    services: 
        logger_service: ~ # if you have own logger service (based on Psr/LoggerInterface)
        command_bus_service: ~ # if you can change the command bus
        router_service: ~ # do you have own router? implement SuRiKmAn\ProcessManagerBundle\CommandBus\Router\RouterInterface
        
    process_manager:
        processes:
            UniqueName: 
                Event: CommandTransformer1
                Event1: CommandTransformer2
                Event2: CommandTransofmer3
                .
                .
                .
        extended_processes: # if you have a parallel processes or process may trigger two different events 
            UniqueName2:
                main:
                    Event: CommandTransformer1
                    Event1: CommandTransformer2
                sub:
                    - 
                        main:
                            Event2a: CommandTransformer3
                        sub:
                            -
                                main: 
                                    Event3: CommandTransformer5
                                    Event4: CommandTransformer5
                            -
                                main:
                                    Event5: CommandTransformer6
                    -
                        main:
                            Event2b: CommandTransformer4
```

```yaml
# Register your own EventHandler

 #e.q.: 
    SuRiKmAn\ProcessManagerBundle\Example\FakeEventHandler1:
        tags:
            - {name: 'surikman_process_manager.event_bus.event_handler'}
            
            
    # Or if you support autoconfiguration just
    _instanceof:
        SuRiKmAn\ProcessManagerBundle\EventBus\Handler\EventHandlerInterface:
            tags: ['surikman_process_manager.event_bus.event_handler']
        SuRiKmAn\ProcessManagerBundle\EventBus\Handler\SubscribedEventHandlerInterface:
            tags: ['surikman_process_manager.event_bus.event_handler']

```

### Create own Handler

#### As Subscriber
```php
// if you can create lazy loaded eventHandler do not use "final" keyword

class MyEventHandler implements SubscribedEventHandlerInterface
{
    public function doSomethingMagic(MyEvent $event): void
    {
        // do something magic...
    }
    
    public function handleAll(EventInterface $event): void
    {
        // do something else...
    }
    
    public function handleWithEventBus(EventInterface $event, EventBusInterface $eventBus): void
    {
        // do something else with EventBus...
        $eventBus->dispatch(new AnotherEvent());
    }
    

    /**
    * ['eventName' => 'methodName'] // priority will be 0
    * ['eventName' => ['methodName', (int)$priority]]
    * ['eventName' => [['methodName1', (int)$priority], ['methodName2']]
    */
    public static function getSubscribedEvents(): array
    {
        return [ 
            MyEvent::class  => 'doSomethingMagic', 
            '*'             => [
                                    ['handleAll', 100], 
                                    ['handleWithEventBus']
                               ]
        ];
    }
}
```
#### As Listener
```php
// if you can create lazy loaded eventHandler do not use "final" keyword
class MyEventHandler implements EventHandlerInterface
{
    public function doSomethingMagic(MyEvent $event): void
    {
        // do something magic...
    }
    
    public function handleAll(EventInterface $event): void
    {
        // do something else...
    }
}
```

and then
```yml
MyEventHandler:
    tags:
        - {name: 'surikman_process_manager.event_bus.event_handler',  event: MyEvent, method: doSomethingMagic, priority: 1}
```