<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListSeasons;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\JsonResponse;

final readonly class ListSeasonsService
{
    public function __construct(
        private SeasonsGateway $gateway,
    ) {
    }

    public function forSeries(string $seriesName): ?JsonResponse
    {
        return JsonResponse::fromData(
            $this->gateway->find($seriesName),
        );
    }
}
