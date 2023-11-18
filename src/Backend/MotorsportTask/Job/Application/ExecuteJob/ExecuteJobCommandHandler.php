<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\ExecuteJob;

use Kishlin\Backend\MotorsportTask\Calendar\Application\Scrap\ScrapCalendarTask;
use Kishlin\Backend\MotorsportTask\Classification\Application\Scrap\ScrapClassificationsTask;
use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobStatus;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobType;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\FindJobGateway;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\SaveJobGateway;
use Kishlin\Backend\MotorsportTask\RaceHistory\Application\Scrap\ScrapRaceHistoriesTask;
use Kishlin\Backend\MotorsportTask\Seasons\Application\Scrap\ScrapSeasonsTask;
use Kishlin\Backend\MotorsportTask\Series\Application\Scrap\ScrapSeriesTask;
use Kishlin\Backend\MotorsportTask\Standing\Application\Scrap\ScrapStandingsTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;

final readonly class ExecuteJobCommandHandler implements CommandHandler
{
    public function __construct(
        private SaveJobGateway $saveJobGateway,
        private FindJobGateway $gateway,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(ExecuteJobCommand $command): bool
    {
        $job = $this->gateway->find($command->job());
        if (null === $job || false === $job->hasStatus(JobStatus::REQUESTED)) {
            return false;
        }

        $job->start();
        $this->saveJobGateway->save($job);

        if ($job->isOfType(JobType::SCRAP_SERIES)) {
            return $this->executeScrapSeriesJob($command);
        }

        if ($job->isOfType(JobType::SCRAP_SEASONS)) {
            return $this->executeScrapSeasonsJob($job, $command);
        }

        if ($job->isOfType(JobType::SCRAP_CALENDAR)) {
            return $this->executeScrapCalendarJob($job, $command);
        }

        if ($job->isOfType(JobType::SCRAP_STANDINGS)) {
            return $this->executeScrapStandingsJob($job, $command);
        }

        if ($job->isOfType(JobType::SCRAP_RACE_HISTORIES)) {
            return $this->executeScrapRaceHistoriesJob($job, $command);
        }

        if ($job->isOfType(JobType::SCRAP_CLASSIFICATIONS)) {
            return $this->executeScrapClassificationsJob($job, $command);
        }

        return false;
    }

    private function executeScrapSeriesJob(ExecuteJobCommand $command): bool
    {
        return $this->taskBus->execute(
            ScrapSeriesTask::forJob($command->job()),
        );
    }

    private function executeScrapSeasonsJob(Job $job, ExecuteJobCommand $command): bool
    {
        return $this->taskBus->execute(
            ScrapSeasonsTask::forSeriesAndJob($job->stringParam('series'), $command->job()),
        );
    }

    private function executeScrapCalendarJob(Job $job, ExecuteJobCommand $command): bool
    {
        return $this->taskBus->execute(
            ScrapCalendarTask::forSeriesAndJob($job->stringParam('series'), $job->intParam('year'), $command->job()),
        );
    }

    private function executeScrapStandingsJob(Job $job, ExecuteJobCommand $command): bool
    {
        return $this->taskBus->execute(
            ScrapStandingsTask::forSeasonAndJob($job->stringParam('series'), $job->intParam('year'), $command->job()),
        );
    }

    private function executeScrapClassificationsJob(Job $job, ExecuteJobCommand $command): bool
    {
        return $this->taskBus->execute(
            ScrapClassificationsTask::forEventAndJob(
                $job->stringParam('series'),
                $job->intParam('year'),
                $job->stringParam('event'),
                $command->job(),
            ),
        );
    }

    private function executeScrapRaceHistoriesJob(Job $job, ExecuteJobCommand $command): bool
    {
        return $this->taskBus->execute(
            ScrapRaceHistoriesTask::forEventAndJob(
                $job->stringParam('series'),
                $job->intParam('year'),
                $job->stringParam('event'),
                $command->job(),
            ),
        );
    }
}
