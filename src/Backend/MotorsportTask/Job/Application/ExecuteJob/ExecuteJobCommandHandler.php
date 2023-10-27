<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\ExecuteJob;

use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobType;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\FindJobGateway;
use Kishlin\Backend\MotorsportTask\Seasons\Application\Scrap\ScrapSeasonsTask;
use Kishlin\Backend\MotorsportTask\Series\Application\Scrap\ScrapSeriesTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;

final readonly class ExecuteJobCommandHandler implements CommandHandler
{
    public function __construct(
        private FindJobGateway $gateway,
        private TaskBus $taskBus,
    ) {
    }

    public function __invoke(ExecuteJobCommand $command): bool
    {
        $job = $this->gateway->find($command->job());
        if (null === $job) {
            return false;
        }

        if ($job->isOfType(JobType::SCRAP_SERIES)) {
            return $this->executeScrapSeriesJob($command);
        }

        if ($job->isOfType(JobType::SCRAP_SEASONS)) {
            return $this->executeScrapSeasonsJob($job, $command);
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
}
