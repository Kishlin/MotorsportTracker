<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar;

final class EventSessionsFilter
{
    private const CANCELLED = 'Cancelled';
    private const POSTPONED = 'Postponed';

    private const QUALIFYING         = 'Qualifying';
    private const QUALIFYING_1       = 'Qualifying 1';
    private const FIRST_QUALIFYING   = '1st Qualifying';
    private const QUALIFYING_GROUP_A = 'Qualifying Group A';

    /**
     * @param array<array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     hasResults: bool,
     *     startTime: ?int,
     *     startTimeUtc: ?int,
     *     endTime: ?int,
     *     endTimeUtc: ?int,
     * }> $sessions
     *
     * @return array<array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     hasResults: bool,
     *     startTime: ?int,
     *     startTimeUtc: ?int,
     *     endTime: ?int,
     *     endTimeUtc: ?int,
     * }>
     */
    public function filterSessions(array $sessions): array
    {
        $hasBuiltQualifyingSession = false;
        $filteredSessions          = [];

        foreach ($sessions as $session) {
            if ($this->isCancelledOrPostponed($session['status'])) {
                continue;
            }

            if (false === $hasBuiltQualifyingSession && $this->shouldBeUsedToBuildQualifying($session['name'])) {
                $filteredSessions[] = [
                    ...$session,
                    'name'      => self::QUALIFYING,
                    'shortName' => '',
                    'code'      => '',
                ];

                continue;
            }

            if ($this->isAFillerSessionAndShouldBeSkipped($session['name'])) {
                continue;
            }

            $filteredSessions[] = [
                ...$session,
                'name'      => $this->mapPracticeSessionName($session['name']),
                'shortName' => '',
                'code'      => '',
            ];
        }

        return $filteredSessions;
    }

    private function isAFillerSessionAndShouldBeSkipped(string $name): bool
    {
        return str_starts_with($name, 'Warm Up')
            || str_starts_with($name, 'Pre-')
            || str_starts_with($name, 'Qualifying ')
            || str_ends_with($name, ' Qualifying')
            || 'Super Pole' === $name;
    }

    private function shouldBeUsedToBuildQualifying(string $name): bool
    {
        return self::QUALIFYING_1 === $name || self::FIRST_QUALIFYING === $name || self::QUALIFYING_GROUP_A === $name;
    }

    private function mapPracticeSessionName(string $source): string
    {
        if ('practice' === lcfirst($source)) {
            return 'Free Practice';
        }

        if (str_starts_with(lcfirst($source), 'practice ')) {
            return 'Free Practice ' . substr($source, 9);
        }

        if ('free practice' === strtolower(substr($source, 4))) {
            return 'Free Practice ' . substr($source, 0, 1);
        }

        if (str_ends_with(strtolower($source), ' practice') && is_numeric(substr($source, 0, 1))) {
            return 'Free Practice ' . substr($source, 0, 1);
        }

        return ucwords($source);
    }

    private function isCancelledOrPostponed(?string $status): bool
    {
        return self::CANCELLED === $status || self::POSTPONED === $status;
    }
}
