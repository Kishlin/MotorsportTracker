<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend;

use Kishlin\Backend\Shared\Infrastructure\DependencyInjection\Symfony\ApplicationServiceCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return __DIR__ . '/..';
    }

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        ApplicationServiceCompilerPass::register($container);
    }
}
