<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Jobs\Application\ListUnfinishedJobs;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\AdminGateway;
use Kishlin\Backend\MotorsportAdmin\Shared\Application\JsonResponse;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobStatus;

final readonly class UnfinishedJobsService
{
    public function __construct(
        private AdminGateway $gateway,
    ) {
    }

    public function forType(string $type): JsonResponse
    {
        return JsonResponse::fromData(
            $this->gateway->find(
                'job',
                [
                    ['type' => $type, 'status' => JobStatus::RUNNING->value],
                    ['type' => $type, 'status' => JobStatus::REQUESTED->value],
                ],
                ['started_on' => 'DESC'],
            ),
        );
    }
}
