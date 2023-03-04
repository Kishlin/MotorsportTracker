<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Car;

use Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar\RegisterCarCommandHandler;
use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarQueryHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\CarRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Car\SearchCarViewerRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SaveSeasonRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;

trait CarServicesTrait
{
    private ?CarRepositorySpy $carRepositorySpy = null;

    private ?SearchCarViewerRepositorySpy $searchCarViewerRepositorySpy = null;

    private ?RegisterCarCommandHandler $registerCarCommandHandler = null;

    private ?SearchCarQueryHandler $searchCarQueryHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function teamRepositorySpy(): TeamRepositorySpy;

    abstract public function seasonRepositorySpy(): SaveSeasonRepositorySpy;

    abstract public function championshipRepositorySpy(): ChampionshipRepositorySpy;

    public function carRepositorySpy(): CarRepositorySpy
    {
        if (null === $this->carRepositorySpy) {
            $this->carRepositorySpy = new CarRepositorySpy($this->teamRepositorySpy(), $this->seasonRepositorySpy());
        }

        return $this->carRepositorySpy;
    }

    public function searchCarViewerRepositorySpy(): SearchCarViewerRepositorySpy
    {
        if (null === $this->searchCarViewerRepositorySpy) {
            $this->searchCarViewerRepositorySpy = new SearchCarViewerRepositorySpy(
                $this->carRepositorySpy(),
                $this->teamRepositorySpy(),
                $this->seasonRepositorySpy(),
                $this->championshipRepositorySpy(),
            );
        }

        return $this->searchCarViewerRepositorySpy;
    }

    public function registerCarCommandHandler(): RegisterCarCommandHandler
    {
        if (null === $this->registerCarCommandHandler) {
            $this->registerCarCommandHandler = new RegisterCarCommandHandler(
                $this->eventDispatcher(),
                $this->uuidGenerator(),
                $this->carRepositorySpy(),
            );
        }

        return $this->registerCarCommandHandler;
    }

    public function searchCarQueryHandler(): SearchCarQueryHandler
    {
        if (null === $this->searchCarQueryHandler) {
            $this->searchCarQueryHandler = new SearchCarQueryHandler(
                $this->searchCarViewerRepositorySpy(),
            );
        }

        return $this->searchCarQueryHandler;
    }
}
