<?php

/**
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\Entity;

use JsonException;
use Kishlin\Backend\MotorsportCache\Standing\Domain\DomainEvent\StandingsViewCreatedDomainEvent;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\ConstructorStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\TeamStandingsView;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Standings extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $championship,
        private readonly IntValueObject $year,
        private readonly ConstructorStandingsView $constructorStandingsView,
        private readonly TeamStandingsView $teamStandingsView,
        private readonly DriverStandingsView $driverStandingsView,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        StringValueObject $championship,
        IntValueObject $year,
        ConstructorStandingsView $constructorStandingsView,
        TeamStandingsView $teamStandingsView,
        DriverStandingsView $driverStandingsView,
    ): self {
        $view = new self($id, $championship, $year, $constructorStandingsView, $teamStandingsView, $driverStandingsView);

        $view->record(new StandingsViewCreatedDomainEvent($id));

        return $view;
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function championship(): StringValueObject
    {
        return $this->championship;
    }

    public function year(): IntValueObject
    {
        return $this->year;
    }

    public function constructorStandingsView(): ConstructorStandingsView
    {
        return $this->constructorStandingsView;
    }

    public function teamStandingsView(): TeamStandingsView
    {
        return $this->teamStandingsView;
    }

    public function driverStandingsView(): DriverStandingsView
    {
        return $this->driverStandingsView;
    }

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'           => $this->id->value(),
            'championship' => $this->championship->value(),
            'year'         => $this->year->value(),
            'constructor'  => $this->constructorStandingsView->asString(),
            'team'         => $this->teamStandingsView->asString(),
            'driver'       => $this->driverStandingsView->asString(),
        ];
    }
}
