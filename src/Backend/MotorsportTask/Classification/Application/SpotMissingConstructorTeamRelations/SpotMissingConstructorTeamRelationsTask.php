<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Classification\Application\SpotMissingConstructorTeamRelations;

use Kishlin\Backend\Shared\Domain\Bus\Task\Task;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class SpotMissingConstructorTeamRelationsTask implements Task
{
    private function __construct(
        private string $series,
        private int $year,
        private string $event,
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

    public function event(): StringValueObject
    {
        return new StringValueObject($this->event);
    }

    public function job(): UuidValueObject
    {
        return new UuidValueObject($this->job);
    }

    public static function forEventAndJob(string $series, int $year, string $event, string $job): self
    {
        return new self($series, $year, $event, $job);
    }
}
