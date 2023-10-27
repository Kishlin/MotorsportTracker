<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListSeasons;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\CoreGateway;
use Kishlin\Backend\MotorsportAdmin\Shared\Application\JsonResponse;

final readonly class ListSeasonsService
{
    public function __construct(
        private CoreGateway $gateway,
    ) {
    }

    public function all(string $seriesName): ?JsonResponse
    {
        $series = $this->gateway->find(
            'series',
            [['name' => $seriesName]],
        );

        if (empty($series)) {
            return null;
        }

        $seriesId = $series[0]['id'];
        assert(is_string($seriesId));

        return JsonResponse::fromData(
            $this->gateway->find(
                'season',
                [['series' => $seriesId]],
                ['year' => 'DESC'],
            ),
        );
    }
}
