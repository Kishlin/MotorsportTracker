<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableCalendarView extends JsonableView
{
    /** @var array<string, array<string, array{championship: string, venue: string, type: string, event: string, date_time: string}>> */
    private array $calendar;

    /**
     * @return array<string, array<string, array{championship: string, venue: string, type: string, event: string, date_time: string}>>
     */
    public function toArray(): array
    {
        return $this->calendar;
    }

    /**
     * @param array<array{date_time: string, championship: string, venue: string, type: string, event: string}> $source
     */
    public static function fromSource(array $source): self
    {
        usort(
            $source,
            static function (array $a, array $b) {
                return strtotime($a['date_time']) - strtotime($b['date_time']);
            },
        );

        $formattedData = [];

        foreach ($source as $item) {
            $formattedData[substr($item['date_time'], 0, 10)][$item['date_time']] = [
                'championship' => $item['championship'],
                'venue'        => $item['venue'],
                'type'         => $item['type'],
                'event'        => $item['event'],
                'date_time'    => $item['date_time'],
            ];
        }

        $view = new self();

        $view->calendar = $formattedData;

        return $view;
    }
}
