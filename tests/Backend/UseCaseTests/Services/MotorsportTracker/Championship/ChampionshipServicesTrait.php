<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommandHandler;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\CreateChampionshipPresentationCommandHandler;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommandHandler;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQueryHandler;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQueryHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\Time\Clock;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SearchSeasonViewerRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;

trait ChampionshipServicesTrait
{
    private ?ChampionshipRepositorySpy $championshipRepositorySpy = null;
    private ?SeasonRepositorySpy $seasonRepositorySpy             = null;

    private ?SearchSeasonViewerRepositorySpy $searchSeasonViewerRepositorySpy = null;

    private ?ChampionshipPresentationRepositorySpy $championshipPresentationRepositorySpy = null;

    private ?CreateChampionshipCommandHandler $createChampionshipCommandHandler                         = null;
    private ?CreateChampionshipPresentationCommandHandler $createChampionshipPresentationCommandHandler = null;
    private ?CreateSeasonCommandHandler $createSeasonCommandHandler                                     = null;
    private ?ViewAllChampionshipsQueryHandler $viewAllChampionshipsQueryHandler                         = null;
    private ?SearchSeasonQueryHandler $searchSeasonQueryHandler                                         = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function clock(): Clock;

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

    public function seasonRepositorySpy(): SeasonRepositorySpy
    {
        if (null === $this->seasonRepositorySpy) {
            $this->seasonRepositorySpy = new SeasonRepositorySpy();
        }

        return $this->seasonRepositorySpy;
    }

    public function searchSeasonViewerRepositorySpy(): SearchSeasonViewerRepositorySpy
    {
        if (null === $this->searchSeasonViewerRepositorySpy) {
            $this->searchSeasonViewerRepositorySpy = new SearchSeasonViewerRepositorySpy(
                $this->championshipRepositorySpy(),
                $this->seasonRepositorySpy(),
            );
        }

        return $this->searchSeasonViewerRepositorySpy;
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

    public function viewAllChampionshipsQueryHandler(): ViewAllChampionshipsQueryHandler
    {
        if (null === $this->viewAllChampionshipsQueryHandler) {
            $this->viewAllChampionshipsQueryHandler = new ViewAllChampionshipsQueryHandler(
                $this->championshipRepositorySpy(),
            );
        }

        return $this->viewAllChampionshipsQueryHandler;
    }

    public function searchSeasonQueryHandler(): SearchSeasonQueryHandler
    {
        if (null === $this->searchSeasonQueryHandler) {
            $this->searchSeasonQueryHandler = new SearchSeasonQueryHandler(
                $this->searchSeasonViewerRepositorySpy(),
            );
        }

        return $this->searchSeasonQueryHandler;
    }
}
