<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ContainerBuilderSpy extends ContainerBuilder
{
    /** @var array<string, int> */
    private array $compilerPassesPriorities = [];

    /** @noinspection PhpUnnecessaryStaticReferenceInspection */
    public function addCompilerPass(
        CompilerPassInterface $pass,
        string $type = PassConfig::TYPE_BEFORE_OPTIMIZATION,
        int $priority = 0,
    ): static {
        $this->compilerPassesPriorities[$pass::class] = $priority;

        return $this;
    }

    public function priorityForCompilerPass(string $class): int
    {
        return $this->compilerPassesPriorities[$class];
    }
}
