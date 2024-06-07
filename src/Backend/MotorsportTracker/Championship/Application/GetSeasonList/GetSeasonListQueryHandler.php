<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final readonly class GetSeasonListQueryHandler implements QueryHandler
{
    public function __construct(
        private SeasonListGateway $gateway,
    ) {}

    public function __invoke(GetSeasonListQuery $query): GetSeasonListResponse
    {
        return GetSeasonListResponse::forDTO(
            $this->gateway->find($query->championshipName()),
        );
    }
}
