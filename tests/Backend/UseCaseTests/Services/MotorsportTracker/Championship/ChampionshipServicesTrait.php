<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\CreateChampionshipIfNotExistsCommandHandler;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\CreateChampionshipPresentationCommandHandler;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\CreateSeasonIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;

trait ChampionshipServicesTrait
{
    private ?ChampionshipRepositorySpy $championshipRepositorySpy = null;
    private ?SaveSeasonRepositorySpy $seasonRepositorySpy         = null;

    private ?ChampionshipPresentationRepositorySpy $championshipPresentationRepositorySpy = null;

    private ?CreateChampionshipIfNotExistsCommandHandler $createChampionshipCommandHandler              = null;
    private ?CreateChampionshipPresentationCommandHandler $createChampionshipPresentationCommandHandler = null;
    private ?CreateSeasonIfNotExistsCommandHandler $createSeasonCommandHandler                          = null;

    public function championshipRepositorySpy(): ChampionshipRepositorySpy
    {
        if (null === $this->championshipRepositorySpy) {
            $this->championshipRepositorySpy = new ChampionshipRepositorySpy();
        }

        return $this->championshipRepositorySpy;
    }

    public function championshipPresentationRepositorySpy(): ChampionshipPresentationRepositorySpy
    {
        if (null === $this->championshipPresentationRepositorySpy) {
            $this->championshipPresentationRepositorySpy = new ChampionshipPresentationRepositorySpy();
        }

        return $this->championshipPresentationRepositorySpy;
    }

    public function seasonRepositorySpy(): SaveSeasonRepositorySpy
    {
        if (null === $this->seasonRepositorySpy) {
            $this->seasonRepositorySpy = new SaveSeasonRepositorySpy();
        }

        return $this->seasonRepositorySpy;
    }

    public function createChampionshipCommandHandler(): CreateChampionshipIfNotExistsCommandHandler
    {
        if (null === $this->createChampionshipCommandHandler) {
            $this->createChampionshipCommandHandler = new CreateChampionshipIfNotExistsCommandHandler(
                $this->championshipRepositorySpy(),
                $this->championshipRepositorySpy(),
                $this->uuidGenerator(),
            );
        }

        return $this->createChampionshipCommandHandler;
    }

    public function createChampionshipPresentationCommandHandler(): CreateChampionshipPresentationCommandHandler
    {
        if (null === $this->createChampionshipPresentationCommandHandler) {
            $this->createChampionshipPresentationCommandHandler = new CreateChampionshipPresentationCommandHandler(
                $this->championshipPresentationRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
                $this->clock(),
            );
        }

        return $this->createChampionshipPresentationCommandHandler;
    }

    public function createSeasonCommandHandler(): CreateSeasonIfNotExistsCommandHandler
    {
        if (null === $this->createSeasonCommandHandler) {
            $this->createSeasonCommandHandler = new CreateSeasonIfNotExistsCommandHandler(
                $this->seasonRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->uuidGenerator(),
                $this->eventDispatcher(),
            );
        }

        return $this->createSeasonCommandHandler;
    }
}
