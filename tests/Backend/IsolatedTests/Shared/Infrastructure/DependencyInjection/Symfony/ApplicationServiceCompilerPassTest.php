<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\DependencyInjection\Symfony;

use Kishlin\Backend\Shared\Infrastructure\DependencyInjection\Symfony\ApplicationServiceCompilerPass;
use Kishlin\Tests\Backend\Tools\Symfony\DependencyInjection\ContainerBuilderSpy;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\DependencyInjection\Symfony\ApplicationServiceCompilerPass
 */
final class ApplicationServiceCompilerPassTest extends TestCase
{
    public function testCompilerPassHasAHighEnoughPriority(): void
    {
        $containerSpy = new ContainerBuilderSpy();

        ApplicationServiceCompilerPass::register($containerSpy);

        $storedPriority = $containerSpy->priorityForCompilerPass(ApplicationServiceCompilerPass::class);

        self::assertLessThan(101, $storedPriority);
        self::assertGreaterThan(0, $storedPriority);
    }
}
