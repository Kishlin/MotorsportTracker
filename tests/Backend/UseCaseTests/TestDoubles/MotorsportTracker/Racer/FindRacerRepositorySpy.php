<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Racer;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate\FindRacerGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;

final class FindRacerRepositorySpy implements FindRacerGateway
{
    public function __construct(
        private ChampionshipRepositorySpy $championshipRepositorySpy,
        private SaveSeasonRepositorySpy $seasonRepositorySpy,
        private RacerRepositorySpy $racerRepositorySpy,
        private CarRepositorySpy $carRepositorySpy,
    ) {
    }

    /**
     * @throws Exception
     */
    public function find(string $driverId, string $championship, string $dateInTimespan): ?Racer
    {
        foreach ($this->racerRepositorySpy->all() as $racer) {
            if ($driverId !== $racer->driverId()->value()) {
                continue;
            }

            if ($this->dateTimeIsInBetweenRacerDates($racer, $dateInTimespan)) {
                continue;
            }

            $refChampionship = $this->getChampionship($racer);

            if ($this->championshipMatchesSearch($refChampionship, $championship)) {
                return $racer;
            }
        }

        return null;
    }

    /**
     * @throws Exception
     */
    private function dateTimeIsInBetweenRacerDates(Racer $racer, string $dateInTimespan): bool
    {
        $dateTime = new DateTimeImmutable($dateInTimespan);

        return $racer->startDate()->value() > $dateTime || $dateTime > $racer->endDate()->value();
    }

    private function getChampionship(Racer $racer): Championship
    {
        return $this
            ->championshipRepositorySpy
            ->safeGet(
                $this
                    ->seasonRepositorySpy
                    ->safeGet(
                        $this->carRepositorySpy->safeGet($racer->carId())->seasonId()
                    )
                    ->championshipId(),
            )
        ;
    }

    private function championshipMatchesSearch(Championship $refChampionship, string $championship): bool
    {
        return str_contains(
            strtolower(str_replace(' ', '', $refChampionship->name()->value() . $refChampionship->slug()->value())),
            strtolower(str_replace(' ', '', $championship))
        );
    }
}
