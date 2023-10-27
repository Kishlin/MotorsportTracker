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

    public function all(): JsonResponse
    {
        return JsonResponse::fromData(
            $this->gateway->find(
                'job',
                [
                    ['status' => JobStatus::RUNNING->value],
                    ['status' => JobStatus::REQUESTED->value],
                ]
            ),
        );
    }
}
