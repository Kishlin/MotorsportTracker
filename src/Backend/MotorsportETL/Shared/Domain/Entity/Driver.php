<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Driver extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly string $name,
        private readonly string $shortCode,
        private readonly string $ref,
        private readonly ?Country $country,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'name'       => $this->name,
            'short_code' => $this->shortCode,
            'ref'        => $this->ref,
            'country'    => $this->country,
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
     * @param array{name: string, shortCode: string, uuid: string} $data
     */
    public static function fromData(array $data, ?Country $country): self
    {
        return new self(
            $data['name'],
            $data['shortCode'],
            $data['uuid'],
            $country,
        );
    }
}
