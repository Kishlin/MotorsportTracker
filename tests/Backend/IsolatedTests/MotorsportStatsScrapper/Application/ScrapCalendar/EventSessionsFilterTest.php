<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportStatsScrapper\Application\ScrapCalendar;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar\EventSessionsFilter;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapCalendar\EventSessionsFilter
 */
final class EventSessionsFilterTest extends TestCase
{
    public function testItChangesWordsToUppercase(): void
    {
        $eventSessionFilter = new EventSessionsFilter();

        self::assertItChangedTheName('Free Practice', $eventSessionFilter->filterSessions($this->buildWithSession('free practice')));
        self::assertItChangedTheName('Free Practice 1', $eventSessionFilter->filterSessions($this->buildWithSession('free practice 1')));
        self::assertItChangedTheName('Combined Qualifying', $eventSessionFilter->filterSessions($this->buildWithSession('combined qualifying')));
    }

    public function testItFormatsFreePractices(): void
    {
        $eventSessionFilter = new EventSessionsFilter();

        self::assertItChangedTheName('Free Practice 1', $eventSessionFilter->filterSessions($this->buildWithSession('1st Free Practice')));
        self::assertItChangedTheName('Free Practice 6', $eventSessionFilter->filterSessions($this->buildWithSession('6th Free practice')));
        self::assertItChangedTheName('Free Practice 3', $eventSessionFilter->filterSessions($this->buildWithSession('3rd Practice')));
    }

    public function testItAddsMissingPrefix(): void
    {
        $eventSessionFilter = new EventSessionsFilter();

        self::assertItChangedTheName('Free Practice', $eventSessionFilter->filterSessions($this->buildWithSession('Practice')));
        self::assertItChangedTheName('Free Practice 3', $eventSessionFilter->filterSessions($this->buildWithSession('Practice 3')));
    }

    public function testItSkipsWarmUps(): void
    {
        $eventSessionFilter = new EventSessionsFilter();

        self::assertItFilteredTheSessionOut(
            $eventSessionFilter->filterSessions($this->buildWithSession('Warm Up')),
        );

        self::assertItFilteredTheSessionOut(
            $eventSessionFilter->filterSessions($this->buildWithSession('Warm Up 2')),
        );
    }

    public function testItSkipsPreQualifying(): void
    {
        $eventSessionFilter = new EventSessionsFilter();

        self::assertItFilteredTheSessionOut(
            $eventSessionFilter->filterSessions($this->buildWithSession('Pre-Qualifying')),
        );

        self::assertItFilteredTheSessionOut(
            $eventSessionFilter->filterSessions($this->buildWithSession('Pre-qualifying')),
        );
    }

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
     * }> $data
     */
    private static function assertItFilteredTheSessionOut(array $data): void
    {
        self::assertCount(0, $data);
    }

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
     * }> $data
     */
    private static function assertItChangedTheName(string $expected, array $data): void
    {
        self::assertCount(1, $data);
        self::assertSame($expected, $data[0]['name']);
    }

    /**
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
    private function buildWithSession(string $name): array
    {
        return [
            [
                'uuid'         => 'bd51cead-a72c-4e6f-849c-10258f29d3c8',
                'name'         => $name,
                'shortName'    => '',
                'shortCode'    => '',
                'status'       => '',
                'hasResults'   => true,
                'startTime'    => 1677853800,
                'startTimeUtc' => 1677843000,
                'endTime'      => 1677857400,
                'endTimeUtc'   => 1677846600,
            ],
        ];
    }
}
