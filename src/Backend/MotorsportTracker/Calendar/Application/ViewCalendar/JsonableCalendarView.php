<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\ViewCalendar;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableCalendarView extends JsonableView
{
    /** @var array<string, array<string, array{championship_slug: string, color: string, icon: string, name: string, type: string, venue_label: string, date_time: string, reference: string}>> */
    private array $calendar;

    /**
     * @return array<string, array<string, array{championship_slug: string, color: string, icon: string, name: string, type: string, venue_label: string, date_time: string, reference: string}>>
     */
    public function toArray(): array
    {
        return $this->calendar;
    }

    /**
     * @param array<array{championship_slug: string, color: string, icon: string, name: string, type: string, venue_label: string, date_time: string, reference: string}> $source
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
                'championship_slug' => $item['championship_slug'],
                'color'             => $item['color'],
                'icon'              => $item['icon'],
                'name'              => $item['name'],
                'type'              => $item['type'],
                'venue_label'       => $item['venue_label'],
                'date_time'         => $item['date_time'],
                'reference'         => $item['reference'],
            ];
        }

        $view = new self();

        $view->calendar = $formattedData;

        return $view;
    }
}
