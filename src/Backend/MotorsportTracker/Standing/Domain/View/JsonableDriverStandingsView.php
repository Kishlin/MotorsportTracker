<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Domain\View;

use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class JsonableDriverStandingsView extends JsonableView
{
    /** @var array<int, array<string, float>> */
    private array $standingsPerEvent = [];

    /**
     * @return array<int, array<string, float>>
     */
    public function toArray(): array
    {
        return $this->standingsPerEvent;
    }

    /**
     * @param array{index: int, driverId: string, points: float}[] $source
     */
    public static function fromSource(array $source): self
    {
        usort(
            $source,
            static function (array $a, array $b) {
                $indexComparison = $a['index'] - $b['index'];

                return 0 === $indexComparison ? $a['points'] - $b['points'] : $indexComparison;
            }
        );

        $formattedData = [];

        foreach ($source as $item) {
            $formattedData[$item['index']][$item['driverId']] = $item['points'];
        }

        $view = new self();

        $view->standingsPerEvent = $formattedData;

        return $view;
    }
}
