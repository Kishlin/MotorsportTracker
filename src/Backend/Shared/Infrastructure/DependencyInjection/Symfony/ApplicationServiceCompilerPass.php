<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\DependencyInjection\Symfony;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ApplicationServiceCompilerPass implements CompilerPassInterface
{
    /**
     * Important that priority remains >0 and <101 for the compiler pass to work.
     *
     * @see PassConfig
     */
    public static function register(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new self(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 50);
    }

    /**
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('kishlin.application_service') as $service => $tag) {
            /** @var class-string<object> $service */
            $message = $this->getMessageClass($service);

            $container->findDefinition($service)->addTag('messenger.message_handler', [
                'handles' => $message,
            ]);
        }
    }

    /**
     * @param class-string<object> $service
     *
     * @throws ReflectionException
     */
    private function getMessageClass(string $service): string
    {
        $reflectionClass = new ReflectionClass($service);

        $parameter = $reflectionClass->getMethod('__invoke')->getParameters()[0];

        /** @var ReflectionNamedType $reflectionIntersectionType */
        $reflectionIntersectionType = $parameter->getType();

        return $reflectionIntersectionType->getName();
    }
}
