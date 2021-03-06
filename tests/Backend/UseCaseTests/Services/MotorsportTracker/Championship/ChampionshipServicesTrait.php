<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommandHandler;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;

trait ChampionshipServicesTrait
{
    private ?ChampionshipRepositorySpy $championshipRepositorySpy = null;
    private ?SeasonRepositorySpy $seasonRepositorySpy             = null;

    private ?CreateChampionshipCommandHandler $createChampionshipCommandHandler = null;
    private ?CreateSeasonCommandHandler $createSeasonCommandHandler             = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    public function championshipRepositorySpy(): ChampionshipRepositorySpy
    {
        if (null === $this->championshipRepositorySpy) {
            $this->championshipRepositorySpy = new ChampionshipRepositorySpy();
        }

        return $this->championshipRepositorySpy;
    }

    public function seasonRepositorySpy(): SeasonRepositorySpy
    {
        if (null === $this->seasonRepositorySpy) {
            $this->seasonRepositorySpy = new SeasonRepositorySpy();
        }

        return $this->seasonRepositorySpy;
    }

    public function createChampionshipCommandHandler(): CreateChampionshipCommandHandler
    {
        if (null === $this->createChampionshipCommandHandler) {
            $this->createChampionshipCommandHandler = new CreateChampionshipCommandHandler(
                $this->uuidGenerator(),
                $this->championshipRepositorySpy(),
                $this->eventDispatcher(),
            );
        }

        return $this->createChampionshipCommandHandler;
    }

    public function createSeasonCommandHandler(): CreateSeasonCommandHandler
    {
        if (null === $this->createSeasonCommandHandler) {
            $this->createSeasonCommandHandler = new CreateSeasonCommandHandler(
                $this->seasonRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createSeasonCommandHandler;
    }
}
