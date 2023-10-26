<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\FlagJobRunning;

use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobStatus;
use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobStartedEvent;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\FindJobGateway;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\SaveJobGateway;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventListener;
use Psr\Log\LoggerInterface;

final readonly class JobStartedEventListener implements EventListener
{
    public function __construct(
        private SaveJobGateway $saveJobGateway,
        private FindJobGateway $findJobGateway,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function __invoke(JobStartedEvent $event): void
    {
        $job = $this->findJobGateway->find($event->uuid());
        if (null === $job) {
            $this->logger?->error('Job not found', ['uuid' => $event->uuid()]);

            $event->flagJobAsNotFound();
            return;
        }

        if (false === $job->hasStatus(JobStatus::REQUESTED)) {
            $this->logger?->warning('Job already started', ['uuid' => $event->uuid()]);

            $event->flagJobAsAlreadyStarted();
            return;
        }

        $job->start();

        $this->saveJobGateway->save($job);

        $this->logger?->info('Job started', ['uuid' => $event->uuid()]);
    }
}
