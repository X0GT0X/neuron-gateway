<?php

namespace App\Infrastructure\Configuration\DependencyInjection;

use App\Infrastructure\Configuration\Decorator\UnitOfWorkCommandHandlerDecorator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandHandlerDecoratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $taggedServices = $container->findTaggedServiceIds(
            'app.command_handler'
        );

        foreach ($taggedServices as $id => $tags) {
            if ($id === UnitOfWorkCommandHandlerDecorator::class) {
                continue;
            }

            $decoratedWithUnitOfWorkServiceId = $this->generateAliasName($id, UnitOfWorkCommandHandlerDecorator::class);
            $container->register($decoratedWithUnitOfWorkServiceId, UnitOfWorkCommandHandlerDecorator::class)
                ->setDecoratedService($id)
                ->setPublic(true)
                ->setAutowired(true);
        }
    }

    private function generateAliasName(string $serviceName, string $decoratorName): string
    {
        $serviceAlias = $this->generateLowercaseName($serviceName);
        $decoratorAlias = $this->generateLowercaseName($decoratorName);

        return $serviceAlias . '_' . $decoratorAlias;
    }

    private function generateLowercaseName(string $serviceName): string
    {
        if (str_contains($serviceName, '\\')) {
            $parts = explode('\\', $serviceName);
            $className = end($parts);
            $lowercaseName = strtolower(
                preg_replace('/[A-Z]/', '_\\0', lcfirst($className))
            );
        } else {
            $lowercaseName = $serviceName;
        }

        return $lowercaseName;
    }
}
