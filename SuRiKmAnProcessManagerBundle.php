<?php
declare(strict_types=1);

namespace SuRiKmAn\ProcessManagerBundle;

use SuRiKmAn\ProcessManagerBundle\DependencyInjection\CompilerPass\CommandHandlerPass;
use SuRiKmAn\ProcessManagerBundle\DependencyInjection\CompilerPass\EventHandlerPass;
use SuRiKmAn\ProcessManagerBundle\DependencyInjection\CompilerPass\MiddlewarePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SuRiKmAnProcessManagerBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EventHandlerPass());
        $container->addCompilerPass(new CommandHandlerPass());
        $container->addCompilerPass(new MiddlewarePass());
    }
}
