<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Service\ActionResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ActionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition(ActionResolver::class);
        $taggedServices = $container->findTaggedServiceIds('app.action');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addAction', [new Reference($id)]);
        }
    }
}