<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Calendar\Application\Scrap;

use Kishlin\Backend\Shared\Domain\Bus\Task\Task;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class ScrapCalendarTask implements Task
{
    private function __construct(
        private string $series,
        private int $year,
        private string $job,
    ) {}

    public function series(): StringValueObject
    {
        return new StringValueObject($this->series);
    }

    public function year(): StrictlyPositiveIntValueObject
    {
        return new StrictlyPositiveIntValueObject($this->year);
    }

    public function job(): UuidValueObject
    {
        return new UuidValueObject($this->job);
    }

    public static function forSeriesAndJob(string $series, int $year, string $job): self
    {
        return new self($series, $year, $job);
    }
}
