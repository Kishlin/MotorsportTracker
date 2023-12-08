<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Events\Application\ListEvents;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\JsonResponse;

final readonly class ListEventsService
{
    public function __construct(
        private EventsGateway $gateway,
        private ResultCounter $resultCounter,
        private EventGraphCounter $eventGraphCounter,
    ) {
    }

    public function forSeries(string $seriesName, int $year): ?JsonResponse
    {
        $eventsFromGateway = $this->gateway->find($seriesName, $year);

        $eventGraphCounter = $this->eventGraphCounter;
        $resultCounter     = $this->resultCounter;

        $eventsData = array_map(
            static function (array $event) use ($eventGraphCounter, $resultCounter): array {
                assert(array_key_exists('id', $event) && is_string($event['id']));

                $graphs  = $eventGraphCounter->graphsForEvent($event['id']);
                $results = $resultCounter->resultsForEvent($event['id']);

                return [
                    ...$event,
                    'count_results' => $results,
                    'count_graphs'  => $graphs,
                ];
            },
            $eventsFromGateway,
        );

        return JsonResponse::fromData(
            $eventsData,
        );
    }
}
