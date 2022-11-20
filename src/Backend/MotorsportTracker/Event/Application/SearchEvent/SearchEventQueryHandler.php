<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class SearchEventQueryHandler implements QueryHandler
{
    public function __construct(
        private SearchEventViewer $viewer,
    ) {
    }

    public function __invoke(SearchEventQuery $query): SearchEventResponse
    {
        $eventId = $this->viewer->search($query->seasonId(), $query->keyword());

        if (null === $eventId) {
            throw new EventNotFoundException();
        }

        return SearchEventResponse::fromScalar($eventId);
    }
}
