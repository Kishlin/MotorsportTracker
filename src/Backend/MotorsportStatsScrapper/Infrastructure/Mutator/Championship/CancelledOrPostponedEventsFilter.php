<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Mutator\Championship;

final class CancelledOrPostponedEventsFilter extends ChampionshipMutator
{
    private const CANCELLED = 'Cancelled';
    private const POSTPONED = 'Postponed';

    /**
     * {@inheritDoc}
     */
    public function preFilter(array $data): array
    {
        $filteredEvents = [];

        foreach ($data['pageProps']['calendar']['events'] as $event) {
            if (self::isCancelledOrPostponed($event['status'])) {
                continue;
            }

            $filteredSessions = array_filter(
                $event['sessions'],
                static function (array $session) {
                    return false === self::isCancelledOrPostponed($session['status']);
                },
            );

            $event['sessions'] = $filteredSessions;
            $filteredEvents[]  = [
                ...$event,
                'sessions' => $filteredSessions,
            ];
        }

        $data['pageProps']['calendar']['events'] = $filteredEvents;

        return $data;
    }

    private static function isCancelledOrPostponed(?string $status): bool
    {
        return self::CANCELLED === $status || self::POSTPONED === $status;
    }
}
