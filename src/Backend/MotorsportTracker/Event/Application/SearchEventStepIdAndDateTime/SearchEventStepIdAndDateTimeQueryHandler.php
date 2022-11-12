<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\SearchEventStepIdAndDateTime;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class SearchEventStepIdAndDateTimeQueryHandler implements QueryHandler
{
    public function __construct(
        private SearchEventStepIdAndDateTimeViewer $viewer,
    ) {
    }

    public function __invoke(SearchEventStepIdAndDateTimeQuery $query): SearchEventStepIdAndDateTimeResponse
    {
        $popo = $this->viewer->search($query->seasonId(), $query->keyword(), $query->eventType());

        if (null === $popo) {
            throw new EventStepNotFoundException();
        }

        return SearchEventStepIdAndDateTimeResponse::fromScalars($popo);
    }
}
