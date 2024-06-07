<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\RecordJob;

use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobType;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class RecordJobCommand implements Command
{
    /**
     * @param array<string, mixed> $params
     */
    private function __construct(
        private JobType $type,
        private array $params,
    ) {}

    public function type(): JobType
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>
     */
    public function params(): array
    {
        return $this->params;
    }

    public static function scrapSeriesJob(): self
    {
        return new self(JobType::SCRAP_SERIES, []);
    }

    public static function scrapSeasons(string $series): self
    {
        return new self(JobType::SCRAP_SEASONS, ['series' => $series]);
    }

    public static function scrapCalendar(string $series, int $season): self
    {
        return new self(JobType::SCRAP_CALENDAR, ['series' => $series, 'year' => $season]);
    }

    public static function scrapStandings(string $series, int $season): self
    {
        return new self(JobType::SCRAP_STANDINGS, ['series' => $series, 'year' => $season]);
    }

    public static function scrapRaceHistories(string $series, int $season, string $event): self
    {
        return new self(JobType::SCRAP_RACE_HISTORIES, ['series' => $series, 'year' => $season, 'event' => $event]);
    }

    public static function scrapClassifications(string $series, int $season, string $event): self
    {
        return new self(JobType::SCRAP_CLASSIFICATIONS, ['series' => $series, 'year' => $season, 'event' => $event]);
    }
}
