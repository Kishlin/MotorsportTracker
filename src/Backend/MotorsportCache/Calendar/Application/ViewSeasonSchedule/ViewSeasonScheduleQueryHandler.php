<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewSeasonSchedule;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewSeasonScheduleQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ViewSeasonScheduleGateway $gateway,
    ) {}

    public function __invoke(ViewSeasonScheduleQuery $query): ViewSeasonScheduleResponse
    {
        return ViewSeasonScheduleResponse::withView(
            $this->gateway->viewSchedule($query->championship(), $query->year()),
        );
    }
}
