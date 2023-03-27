<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Result;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\RaceResultDTO;
use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsByRace\RaceResultGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Driver\DriverRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\EventSessionRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event\SaveEventRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\ClassificationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result\EntryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;
use RuntimeException;

final class RaceResultRepositorySpy implements RaceResultGateway
{
    /** @var array<string, array<string, TeamPresentation>> */
    private array $teamCache;

    public function __construct(
        private readonly TeamPresentationRepositorySpy $teamPresentationRepositorySpy,
        private readonly ClassificationRepositorySpy $classificationRepositorySpy,
        private readonly EventSessionRepositorySpy $eventSessionRepositorySpy,
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
        private readonly SaveSeasonRepositorySpy $seasonRepositorySpy,
        private readonly SaveEventRepositorySpy $eventRepositorySpy,
        private readonly DriverRepositorySpy $driverRepositorySpy,
        private readonly EntryRepositorySpy $entryRepositorySpy,
        private readonly TeamRepositorySpy $teamRepositorySpy,
    ) {
        $this->teamCache = [];
    }

    public function findResult(string $eventSessionId): RaceResultDTO
    {
        $results = [];

        foreach ($this->classificationRepositorySpy->all() as $classification) {
            $entry = $this->entryRepositorySpy->safeGet($classification->entry());
            if ($eventSessionId !== $entry->session()->value()) {
                continue;
            }

            $session  = $this->eventSessionRepositorySpy->safeGet($entry->session());
            $event    = $this->eventRepositorySpy->safeGet($session->eventId());
            $driver   = $this->driverRepositorySpy->safeGet($entry->driver());
            $team     = $this->teamRepositorySpy->safeGet($entry->team());
            $season   = $this->seasonRepositorySpy->safeGet($event->seasonId());
            $teamPres = $this->memoizedTeamPresentation($team->id(), $season->id());
            $driverC  = $this->countryRepositorySpy->safeGet($driver->countryId());
            $teamC    = $this->countryRepositorySpy->safeGet($teamPres->country());

            $results[] = [
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
                    'id'              => $team->id()->value(),
                    'presentation_id' => $teamPres->id()->value(),
                    'name'            => $teamPres->name()->value(),
                    'color'           => $teamPres->color()->value(),
                    'country'         => [
                        'id'   => $teamC->id()->value(),
                        'code' => $teamC->code()->value(),
                        'name' => $teamC->name()->value(),
                    ],
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
        }

        return RaceResultDTO::fromResults($results);
    }

    public function memoizedTeamPresentation(UuidValueObject $team, UuidValueObject $season): TeamPresentation
    {
        if (false === array_key_exists($team->value(), $this->teamCache)) {
            $this->teamCache[$team->value()] = [];
        }

        if (false === array_key_exists($season->value(), $this->teamCache[$team->value()])) {
            foreach ($this->teamPresentationRepositorySpy->all() as $teamPresentation) {
                if ($teamPresentation->team()->equals($team) && $teamPresentation->season()->equals($season)) {
                    $this->teamCache[$team->value()][$season->value()] = $teamPresentation;

                    return $teamPresentation;
                }
            }

            throw new RuntimeException('No Team Presentation match criteria.');
        }

        return $this->teamCache[$team->value()][$season->value()];
    }
}
