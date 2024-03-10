<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Infrastructure\InvalidateFrontCacheOnJobFinished;

use Kishlin\Backend\MotorsportTask\Job\Application\InvalidateFrontCacheOnJobFinished\FrontCacheInvalidator;
use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobType;
use Kishlin\Backend\MotorsportTask\Shared\Application\EventIdGateway;
use Kishlin\Backend\Tools\Helpers\StringHelper;
use Psr\Log\LoggerInterface;

final readonly class FrontCacheInvalidatorUsingConnector implements FrontCacheInvalidator
{
    public function __construct(
        private EventIdGateway $eventIdGateway,
        private FrontConnector $connector,
        private ?LoggerInterface $logger,
    ) {
    }

    public function invalidateAfter(Job $job): bool
    {
        if ($job->isOfType(JobType::SCRAP_RACE_HISTORIES)) {
            return $this->invalidateAfterARaceHistoriesJob($job);
        }

        if ($job->isOfType(JobType::SCRAP_CALENDAR)) {
            return $this->invalidateAfterACalendarJob($job);
        }

        if ($job->isOfType(JobType::SCRAP_STANDINGS)) {
            return $this->invalidateAfterAStandingsJob($job);
        }

        if ($job->isOfType(JobType::SCRAP_CLASSIFICATIONS)) {
            return $this->invalidateAfterAClassificationsJob($job);
        }

        return false;
    }

    private function invalidateAfterARaceHistoriesJob(Job $job): bool
    {
        $eventId = $this->findEventIdForJob($job);
        if (null === $eventId) {
            return false;
        }

        $this->connector->invalidateCacheTag("histories_{$eventId}");

        return true;
    }

    private function invalidateAfterAClassificationsJob(Job $job): bool
    {
        $eventId = $this->findEventIdForJob($job);
        if (null === $eventId) {
            return false;
        }

        $this->connector->invalidateCacheTag("classifications_{$eventId}");

        return true;
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

    private function findEventIdForJob(Job $job): null|string
    {
        $series = $job->stringParam('series');
        $year   = $job->intParam('year');
        $event  = $job->stringParam('event');

        $eventId = $this->eventIdGateway->findEventId($series, $year, $event);

        if (null === $eventId) {
            $this->logger?->warning('Event not found', ['series' => $series, 'year' => $year, 'event' => $event]);
        }

        return $eventId;
    }
}
