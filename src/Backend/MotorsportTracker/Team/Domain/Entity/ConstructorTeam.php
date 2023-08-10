<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use RuntimeException;

final class ConstructorTeam extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $constructor,
        private readonly UuidValueObject $team,
    ) {
    }

    public static function create(UuidValueObject $constructor, UuidValueObject $team): self
    {
        return new self($constructor, $team);
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $constructor, UuidValueObject $team): self
    {
        return new self($constructor, $team);
    }

    public function id(): UuidValueObject
    {
        throw new RuntimeException('This entity does not have an id.');
    }

    public function constructor(): UuidValueObject
    {
        return $this->constructor;
    }

    public function team(): UuidValueObject
    {
        return $this->team;
    }

    public function mappedData(): array
    {
        return [
            'constructor' => $this->constructor->value(),
            'team'        => $this->team->value(),
        ];
    }
}
