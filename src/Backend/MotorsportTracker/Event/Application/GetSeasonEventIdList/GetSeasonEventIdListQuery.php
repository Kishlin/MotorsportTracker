<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final readonly class GetSeasonEventIdListQuery implements Query
{
    private function __construct(
        private string $championshipName,
        private int $year,
        private ?string $eventFilter,
    ) {}

    public function championshipName(): StringValueObject
    {
        return new StringValueObject($this->championshipName);
    }

    public function year(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->year);
    }

    public function eventFilter(): NullableStringValueObject
    {
        return new NullableStringValueObject($this->eventFilter);
    }

    public static function fromScalars(string $championshipName, int $year, ?string $eventFilter): self
    {
        return new self($championshipName, $year, $eventFilter);
    }
}
