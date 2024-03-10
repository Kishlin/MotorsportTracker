<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Infrastructure\InvalidateFrontCacheOnJobFinished;

use Kishlin\Backend\MotorsportTask\Job\Application\InvalidateFrontCacheOnJobFinished\FrontCacheInvalidator;
use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobType;
use Kishlin\Backend\Tools\Helpers\StringHelper;

final readonly class FrontCacheInvalidatorUsingConnector implements FrontCacheInvalidator
{
    public function __construct(
        private FrontConnector $connector,
    ) {
    }

    public function invalidateAfter(Job $job): bool
    {
        if ($job->isOfType(JobType::SCRAP_CALENDAR)) {
            return $this->invalidateAfterACalendarJob($job);
        }

        if ($job->isOfType(JobType::SCRAP_STANDINGS)) {
            return $this->invalidateAfterAStandingsJob($job);
        }

        return false;
    }

    private function invalidateAfterAStandingsJob(Job $job): bool
    {
        $series = StringHelper::slugify($job->stringParam('series'));
        $year   = $job->intParam('year');

        $this->connector->invalidateCacheTag("stats_{$series}_{$year}");


        return true;
    }

    private function invalidateAfterACalendarJob(Job $job): bool
    {
        $series = StringHelper::slugify($job->stringParam('series'));
        $year   = $job->intParam('year');

        $this->connector->invalidateCacheTag("calendar_{$series}_{$year}");
        $this->connector->invalidateCacheTag((string) $year);

        return true;
    }
}
