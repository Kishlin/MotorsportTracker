<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\CreateTeamCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\CountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;

trait TeamServicesTrait
{
    private ?TeamRepositorySpy $teamRepositorySpy = null;

    private ?CreateTeamCommandHandler $createTeamCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function countryRepositorySpy(): CountryRepositorySpy;

    public function teamRepositorySpy(): TeamRepositorySpy
    {
        if (null === $this->teamRepositorySpy) {
            $this->teamRepositorySpy = new TeamRepositorySpy($this->countryRepositorySpy());
        }

        return $this->teamRepositorySpy;
    }

    public function createTeamCommandHandler(): CreateTeamCommandHandler
    {
        if (null === $this->createTeamCommandHandler) {
            $this->createTeamCommandHandler = new CreateTeamCommandHandler(
                $this->eventDispatcher(),
                $this->uuidGenerator(),
                $this->teamRepositorySpy(),
            );
        }

        return $this->createTeamCommandHandler;
    }
}
