<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class FindEntryForSessionAndNumberQuery implements Query
{
    private function __construct(
        private readonly string $session,
        private readonly int $number,
    ) {
    }

    public function session(): UuidValueObject
    {
        return new UuidValueObject($this->session);
    }

    public function number(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->number);
    }

    public static function fromScalars(string $session, int $number): self
    {
        return new self($session, $number);
    }
}
