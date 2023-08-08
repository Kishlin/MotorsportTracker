<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\DTO\SessionResultDTO;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\SessionClassificationGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\ClassificationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\EntryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;

final readonly class SessionClassificationRepositorySpy implements SessionClassificationGateway
{
    public function __construct(
        private ClassificationRepositorySpy $classificationRepositorySpy,
        private SaveSearchCountryRepositorySpy $countryRepositorySpy,
        private DriverRepositorySpy $driverRepositorySpy,
        private EntryRepositorySpy $entryRepositorySpy,
        private TeamRepositorySpy $teamRepositorySpy,
    ) {
    }

    public function findResult(string $eventSessionId): SessionResultDTO
    {
        $results = [];

        foreach ($this->classificationRepositorySpy->all() as $classification) {
            $entry = $this->entryRepositorySpy->safeGet($classification->entry());
            if ($eventSessionId !== $entry->session()->value()) {
                continue;
            }

            $driverC = $this->countryRepositorySpy->safeGet($entry->country());
            $driver  = $this->driverRepositorySpy->safeGet($entry->driver());
            $team    = $this->teamRepositorySpy->safeGet($entry->team());

            $result = [
                'driver' => [
                    'id'         => $driver->id()->value(),
                    'short_code' => $driver->shortCode()->value(),
                    'name'       => $driver->name()->value(),
                    'country'    => [
                        'id'   => $driverC->id()->value(),
                        'code' => $driverC->code()->value(),
                        'name' => $driverC->name()->value(),
                    ],
                ],
                'team' => [
                    'id'      => $team->id()->value(),
                    'name'    => $team->name()->value(),
                    'color'   => $team->color()->value(),
                    'country' => null,
                ],
                'car_number'        => $entry->carNumber()->value(),
                'finish_position'   => $classification->finishPosition()->value(),
                'grid_position'     => $classification->gridPosition()->value(),
                'classified_status' => $classification->classifiedStatus()->value(),
                'laps'              => $classification->laps()->value(),
                'points'            => $classification->points()->value(),
                'race_time'         => $classification->time()->value(),
                'average_lap_speed' => $classification->averageLapSpeed()->value(),
                'best_lap_time'     => $classification->fastestLapTime()->value(),
                'best_lap'          => $classification->bestLap()->value(),
                'best_is_fastest'   => $classification->bestIsFastest()->value(),
                'gap_time'          => $classification->gapTimeToLead()->value(),
                'interval_time'     => $classification->gapTimeToNext()->value(),
                'gap_laps'          => $classification->gapLapsToLead()->value(),
                'interval_laps'     => $classification->gapLapsToNext()->value(),
            ];

            if (null !== $team->country()->value()) {
                $teamC = $this->countryRepositorySpy->safeGet(new UuidValueObject($team->country()->value()));

                $result['team']['country'] = [
                    'id'   => $teamC->id()->value(),
                    'code' => $teamC->code()->value(),
                    'name' => $teamC->name()->value(),
                ];
            }

            $results[] = $result;
        }

        return SessionResultDTO::fromResults($results);
    }
}
