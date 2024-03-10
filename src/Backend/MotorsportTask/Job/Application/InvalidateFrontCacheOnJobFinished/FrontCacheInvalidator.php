<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\InvalidateFrontCacheOnJobFinished;

use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;

interface FrontCacheInvalidator
{
    public function invalidateAfter(Job $job): bool;
}
