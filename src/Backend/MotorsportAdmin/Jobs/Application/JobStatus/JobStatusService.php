<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Jobs\Application\JobStatus;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\AdminGateway;
use Kishlin\Backend\MotorsportAdmin\Shared\Application\JsonResponse;

final readonly class JobStatusService
{
    public function __construct(
        private AdminGateway $gateway,
    ) {}

    public function status(string $id): ?JsonResponse
    {
        $jobs = $this->gateway->find('job', [['id' => $id]], limit: 1);

        if (empty($jobs)) {
            return null;
        }

        return JsonResponse::fromData($jobs[0]);
    }
}
