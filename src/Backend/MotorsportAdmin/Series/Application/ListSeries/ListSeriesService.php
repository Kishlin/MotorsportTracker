<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Series\Application\ListSeries;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\CoreGateway;
use Kishlin\Backend\MotorsportAdmin\Shared\Application\JsonResponse;

final readonly class ListSeriesService
{
    public function __construct(
        private CoreGateway $gateway,
    ) {
    }

    public function all(): JsonResponse
    {
        return JsonResponse::fromData(
            $this->gateway->find(
                'series',
            ),
        );
    }
}