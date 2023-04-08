<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewGraphDataForEventQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GraphDataForEventGateway $gateway,
    ) {
    }

    public function __invoke(ViewGraphDataForEventQuery $query): ViewGraphDataForEventResponse
    {
        return ViewGraphDataForEventResponse::withView(
            $this->gateway->viewForEvent($query->eventId()),
        );
    }
}
