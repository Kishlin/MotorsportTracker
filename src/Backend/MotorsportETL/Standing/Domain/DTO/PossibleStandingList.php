<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\DTO;

final readonly class PossibleStandingList
{
    private function __construct(
        private PossibleStandingCategory $constructorStandings,
        private PossibleStandingCategory $driversStandings,
        private PossibleStandingCategory $teamStandings,
    ) {
    }

    public function constructorStandings(): PossibleStandingCategory
    {
        return $this->constructorStandings;
    }

    public function driversStandings(): PossibleStandingCategory
    {
        return $this->driversStandings;
    }

    public function teamStandings(): PossibleStandingCategory
    {
        return $this->teamStandings;
    }

    /**
     * @param array<null|array{uuid: string, name: string}> $constructorStandings
     * @param array<null|array{uuid: string, name: string}> $driversStandings
     * @param array<null|array{uuid: string, name: string}> $teamStandings
     */
    public static function forStandingsList(array $constructorStandings, array $driversStandings, array $teamStandings): self
    {
        return new self(
            PossibleStandingCategory::forStandings($constructorStandings),
            PossibleStandingCategory::forStandings($driversStandings),
            PossibleStandingCategory::forStandings($teamStandings),
        );
    }
}
