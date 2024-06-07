<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Constructor extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly string $name,
        private readonly string $ref,
    ) {}

    public function name(): StringValueObject
    {
        return new StringValueObject($this->name);
    }

    public function ref(): UuidValueObject
    {
        return new UuidValueObject($this->ref);
    }

    public function mappedData(): array
    {
        return [
            'name' => $this->name,
            'ref'  => $this->ref,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['name'],
            ['ref'],
        ];
    }

    /**
     * @param array{name: string, uuid: string} $data
     *
     * @return static
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['name'],
            $data['uuid'],
        );
    }
}
