<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\ConstructorTeamCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ConstructorTeam extends AggregateRoot
{
    public function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $constructor,
        private readonly UuidValueObject $team,
    ) {
    }

    public static function create(UuidValueObject $id, UuidValueObject $constructor, UuidValueObject $team): self
    {
        $team = new self($id, $constructor, $team);

        $team->record(new ConstructorTeamCreatedDomainEvent($id));

        return $team;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $id, UuidValueObject $constructor, UuidValueObject $team): self
    {
        return new self($id, $constructor, $team);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
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
            'id'          => $this->id->value(),
            'constructor' => $this->constructor->value(),
            'team'        => $this->team->value(),
        ];
    }
}
