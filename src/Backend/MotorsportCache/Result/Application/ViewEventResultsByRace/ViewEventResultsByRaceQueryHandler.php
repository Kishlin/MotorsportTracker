<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\ViewEventResultsByRace;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewEventResultsByRaceQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ViewEventResultsByRaceGateway $gateway,
    ) {
    }

    public function __invoke(ViewEventResultsByRaceQuery $query): ViewEventResultsByRaceResponse
    {
        return ViewEventResultsByRaceResponse::withView(
            $this->gateway->viewForEvent($query->event()),
        );
    }
}
