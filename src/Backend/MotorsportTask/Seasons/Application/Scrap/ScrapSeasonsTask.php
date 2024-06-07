<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Seasons\Application\Scrap;

use Kishlin\Backend\Shared\Domain\Bus\Task\Task;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class ScrapSeasonsTask implements Task
{
    private function __construct(
        private string $series,
        private string $job,
    ) {}

    public function series(): StringValueObject
    {
        return new StringValueObject($this->series);
    }

    public function job(): UuidValueObject
    {
        return new UuidValueObject($this->job);
    }

    public static function forSeriesAndJob(string $series, string $job): self
    {
        return new self($series, $job);
    }
}
