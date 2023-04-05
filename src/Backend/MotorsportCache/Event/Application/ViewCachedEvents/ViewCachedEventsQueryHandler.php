<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewCachedEventsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ViewCachedEventsGateway $gateway,
    ) {
    }

    public function __invoke(ViewCachedEventsQuery $query): ViewCachedEventsResponse
    {
        return ViewCachedEventsResponse::withView(
            $this->gateway->findAll(),
        );
    }
}
