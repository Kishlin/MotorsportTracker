<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\CreateTeamIfNotExistsCommandHandler;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\CreateTeamPresentationIfNotExistsCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamRepositorySpy;

trait TeamServicesTrait
{
    private ?TeamRepositorySpy $teamRepositorySpy = null;

    private ?TeamPresentationRepositorySpy $teamPresentationRepositorySpy = null;

    private ?CreateTeamIfNotExistsCommandHandler $createTeamIfNotExistsCommandHandler = null;

    private ?CreateTeamPresentationIfNotExistsCommandHandler $createTeamPresentationIfNotExistsCommandHandler = null;

    public function teamRepositorySpy(): TeamRepositorySpy
    {
        if (null === $this->teamRepositorySpy) {
            $this->teamRepositorySpy = new TeamRepositorySpy();
        }

        return $this->teamRepositorySpy;
    }

    public function teamPresentationRepositorySpy(): TeamPresentationRepositorySpy
    {
        if (null === $this->teamPresentationRepositorySpy) {
            $this->teamPresentationRepositorySpy = new TeamPresentationRepositorySpy(
                $this->countryRepositorySpy()
            );
        }

        return $this->teamPresentationRepositorySpy;
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

    public function createTeamPresentationIfNotExistsCommandHandler(): CreateTeamPresentationIfNotExistsCommandHandler
    {
        if (null === $this->createTeamPresentationIfNotExistsCommandHandler) {
            $this->createTeamPresentationIfNotExistsCommandHandler = new CreateTeamPresentationIfNotExistsCommandHandler(
                $this->teamPresentationRepositorySpy(),
                $this->teamPresentationRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
            );
        }

        return $this->createTeamPresentationIfNotExistsCommandHandler;
    }
}
