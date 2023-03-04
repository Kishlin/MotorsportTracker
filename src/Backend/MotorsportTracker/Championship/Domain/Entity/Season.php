<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Season extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StrictlyPositiveIntValueObject $year,
        private readonly UuidValueObject $championshipId,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StrictlyPositiveIntValueObject $year,
        UuidValueObject $championshipId,
    ): self {
        return new self($id, $year, $championshipId);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        StrictlyPositiveIntValueObject $year,
        UuidValueObject $championshipId,
    ): self {
        return new self($id, $year, $championshipId);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function year(): StrictlyPositiveIntValueObject
    {
        return $this->year;
    }

    public function championshipId(): UuidValueObject
    {
        return $this->championshipId;
    }
}
