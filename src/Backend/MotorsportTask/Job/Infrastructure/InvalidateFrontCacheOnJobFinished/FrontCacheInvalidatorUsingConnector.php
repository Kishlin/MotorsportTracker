<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Infrastructure\InvalidateFrontCacheOnJobFinished;

use Kishlin\Backend\MotorsportTask\Job\Application\InvalidateFrontCacheOnJobFinished\FrontCacheInvalidator;
use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;

final readonly class FrontCacheInvalidatorUsingConnector implements FrontCacheInvalidator
{
    public function __construct(
        private FrontConnector $connector,
    ) {
    }

    public function invalidateAfter(Job $job): void
    {
        // TODO: Implement invalidateAfter() method.
    }
}
