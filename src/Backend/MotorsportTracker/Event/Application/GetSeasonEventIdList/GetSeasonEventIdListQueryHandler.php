<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final readonly class GetSeasonEventIdListQueryHandler implements QueryHandler
{
    public function __construct(
        private SeasonEventIdListGateway $gateway,
    ) {
    }

    public function __invoke(GetSeasonEventIdListQuery $query): GetSeasonEventIdListResponse
    {
        return GetSeasonEventIdListResponse::forDTO(
            $this->gateway->find($query->championshipName(), $query->year(), $query->eventFilter()),
        );
    }
}
