<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\ViewCalendarEvents;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableCalendarEventsView extends JsonableView
{
    /**
     * @var array<string, array<int, array{
     *     id: string,
     *     reference: string,
     *     slug: string,
     *     index: int,
     *     name: string,
     *     short_name: ?string,
     *     short_code: ?string,
     *     start_date: string,
     *     end_date: string,
     *     series: array<string, int|string>,
     *     sessions: array<array<string, int|string>>,
     *     venue: array<string, string|array<string, string>>
     * }>>
     */
    private array $calendar;

    /**
     * @return array<string, array<int, array{
     *     id: string,
     *     reference: string,
     *     slug: string,
     *     index: int,
     *     name: string,
     *     short_name: ?string,
     *     short_code: ?string,
     *     start_date: string,
     *     end_date: string,
     *     series: array<string, int|string>,
     *     sessions: array<array<string, int|string>>,
     *     venue: array<string, string|array<string, string>>
     * }>>
     */
    public function toArray(): array
    {
        return $this->calendar;
    }

    /**
     * @param array<array{id: string, reference: string, slug: string, index: int, name: string, short_name: ?string, short_code: ?string, start_date: string, end_date: string, series: string, venue: string, sessions: string}> $source
     */
    public static function fromSource(array $source): self
    {
        $formattedData = [];

        foreach ($source as $item) {
            /** @var array{id: string, reference: string, slug: string, index: int, name: string, short_name: ?string, short_code: ?string, start_date: string, end_date: string, series: array<string, int|string>, sessions: array<array<string, int|string>>,venue: array<string, array<string, string>|string>} $formatted */
            $formatted = [
                ...$item,
                'sessions' => json_decode($item['sessions'], true),
                'venue'    => json_decode($item['venue'], true),
                'series'   => unserialize($item['series']),
            ];

            $formattedData[substr($item['start_date'], 0, 10)][] = $formatted;
        }

        $view = new self();

        $view->calendar = $formattedData;

        return $view;
    }
}
