<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Service\Registry\BotActionRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BotActionPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(BotActionRegistry::class)) {
            return;
        }

        $definition = $container->findDefinition(BotActionRegistry::class);
        $services   = $container->findTaggedServiceIds('bot.action');

        foreach ($services as $id => $tags) {
            $definition->addMethodCall('addAction', [new Reference($id)]);
        }
    }
}
