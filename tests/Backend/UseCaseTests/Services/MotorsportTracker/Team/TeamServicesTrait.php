<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\CreateTeamIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;

trait TeamServicesTrait
{
    private ?TeamRepositorySpy $teamRepositorySpy = null;

    private ?CreateTeamIfNotExistsCommandHandler $createTeamIfNotExistsCommandHandler = null;

    public function teamRepositorySpy(): TeamRepositorySpy
    {
        if (null === $this->teamRepositorySpy) {
            $this->teamRepositorySpy = new TeamRepositorySpy();
        }

        return $this->teamRepositorySpy;
    }

    public function createTeamIfNotExistsCommandHandler(): CreateTeamIfNotExistsCommandHandler
    {
        if (null === $this->createTeamIfNotExistsCommandHandler) {
            $this->createTeamIfNotExistsCommandHandler = new CreateTeamIfNotExistsCommandHandler(
                $this->teamRepositorySpy(),
                $this->teamRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createTeamIfNotExistsCommandHandler;
    }
}
