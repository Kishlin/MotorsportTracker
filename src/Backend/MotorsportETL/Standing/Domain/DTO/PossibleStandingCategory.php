<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\DTO;

use Generator;
use LogicException;

final readonly class PossibleStandingCategory
{
    /**
     * @param null|PossibleStandingClass[] $standings
     */
    private function __construct(
        private ?array $standings,
    ) {}

    public function isAvailable(): bool
    {
        return null !== $this->standings;
    }

    public function isMultiClass(): bool
    {
        return false === empty($this->standings);
    }

    /**
     * @return Generator<PossibleStandingClass>
     */
    public function classes(): Generator
    {
        if (empty($this->standings)) {
            throw new LogicException('These standings are not multi class');
        }

        yield from $this->standings;
    }

    /**
     * @param array<array{uuid: string, name: string}>|null[] $standings
     */
    public static function forStandings(array $standings): self
    {
        if (empty($standings)) {
            return new self(null);
        }

        if (null === $standings[0]) {
            return new self([]);
        }

        /** @var array<array{uuid: string, name: string}> $standings */

        return new self(
            array_map(
                static fn (array $standing): PossibleStandingClass => PossibleStandingClass::forStanding($standing),
                $standings,
            ),
        );
    }
}
