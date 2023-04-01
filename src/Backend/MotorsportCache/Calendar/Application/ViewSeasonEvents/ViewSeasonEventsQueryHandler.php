<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonEvents;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewSeasonEventsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ViewSeasonEventsGateway $gateway,
    ) {
    }

    public function __invoke(ViewSeasonEventsQuery $query): ViewSeasonEventsResponse
    {
        return ViewSeasonEventsResponse::withView(
            $this->gateway->viewForSeason($query->championshipSlug(), $query->year()),
        );
    }
}
