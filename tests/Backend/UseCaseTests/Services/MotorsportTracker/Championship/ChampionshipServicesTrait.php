<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\SeasonRepositorySpy;

trait ChampionshipServicesTrait
{
    abstract public function eventDispatcher(): EventDispatcher;
    abstract public function uuidGenerator(): UuidGenerator;

    private ?ChampionshipRepositorySpy $championshipRepositorySpy = null;
    private ?SeasonRepositorySpy $seasonRepositorySpy = null;

    private ?CreateChampionshipCommandHandler $createChampionshipCommandHandler = null;

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
}
