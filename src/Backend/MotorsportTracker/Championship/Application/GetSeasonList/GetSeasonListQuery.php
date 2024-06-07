<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final readonly class GetSeasonListQuery implements Query
{
    private function __construct(
        private string $championshipName,
    ) {}

    public function championshipName(): StringValueObject
    {
        return new StringValueObject($this->championshipName);
    }

    public static function fromScalars(string $championshipName): self
    {
        return new self($championshipName);
    }
}
