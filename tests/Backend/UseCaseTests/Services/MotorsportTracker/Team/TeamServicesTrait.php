<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\CreateTeamCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\SaveTeamRepositorySpy;

trait TeamServicesTrait
{
    private ?SaveTeamRepositorySpy $teamRepositorySpy = null;

    private ?CreateTeamCommandHandler $createTeamCommandHandler = null;

    public function teamRepositorySpy(): SaveTeamRepositorySpy
    {
        if (null === $this->teamRepositorySpy) {
            $this->teamRepositorySpy = new SaveTeamRepositorySpy($this->countryRepositorySpy());
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
