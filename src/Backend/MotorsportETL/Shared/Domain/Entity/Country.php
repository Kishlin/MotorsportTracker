<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain\Entity;

use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;
use RuntimeException;

final class Country extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly ?string $code,
        private readonly string $name,
        private readonly string $ref,
    ) {}

    public function mappedData(): array
    {
        return [
            'code' => $this->code,
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
     * @param array{name: string, uuid: string, picture: ?string} $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            self::computeCode($data['picture']),
            $data['name'],
            $data['uuid'],
        );
    }

    private static function computeCode(?string $picture): ?string
    {
        if (null === $picture) {
            return null;
        }

        if ('/' === $picture[-6]) {
            throw new RuntimeException("Unexpected Country Picture format: {$picture}");
        }

        return substr($picture, -6, 2);
    }
}
