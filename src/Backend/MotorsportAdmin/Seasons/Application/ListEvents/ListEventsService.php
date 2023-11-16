<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListEvents;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\JsonResponse;

final readonly class ListEventsService
{
    public function __construct(
        private EventsGateway $gateway,
    ) {
    }

    public function forSeries(string $seriesName, int $year): ?JsonResponse
    {
        return JsonResponse::fromData(
            $this->gateway->find($seriesName, $year),
        );
    }
}
