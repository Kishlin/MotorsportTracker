<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\FlagJobFinished;

use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\FindJobGateway;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\SaveJobGateway;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventListener;
use Kishlin\Backend\Shared\Domain\Time\Clock;
use Psr\Log\LoggerInterface;

final readonly class EventFinishedListener implements EventListener
{
    public function __construct(
        private SaveJobGateway $saveJobGateway,
        private FindJobGateway $findJobGateway,
        private Clock $clock,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function __invoke(JobFinishedEvent $event): void
    {
        $job = $this->findJobGateway->find($event->uuid());
        if (null === $job) {
            $this->logger?->error('Job not found', ['uuid' => $event->uuid()]);

            return;
        }

        $job->end($this->clock->now());

        $this->saveJobGateway->save($job);

        $this->logger?->info('Job finished', ['uuid' => $event->uuid()]);
    }
}
