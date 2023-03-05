<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\CreateTeamPresentationCommandHandler;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamPresentationRepositorySpy;

trait TeamPresentationServicesTrait
{
    private ?TeamPresentationRepositorySpy $teamPresentationRepositorySpy = null;

    private ?CreateTeamPresentationCommandHandler $createTeamPresentationCommandHandler = null;

    public function teamPresentationRepositorySpy(): TeamPresentationRepositorySpy
    {
        if (null === $this->teamPresentationRepositorySpy) {
            $this->teamPresentationRepositorySpy = new TeamPresentationRepositorySpy();
        }

        return $this->teamPresentationRepositorySpy;
    }

    public function createTeamPresentationCommandHandler(): CreateTeamPresentationCommandHandler
    {
        if (null === $this->createTeamPresentationCommandHandler) {
            $this->createTeamPresentationCommandHandler = new CreateTeamPresentationCommandHandler(
                $this->teamPresentationRepositorySpy(),
                $this->eventDispatcher(),
                $this->uuidGenerator(),
                $this->clock(),
            );
        }

        return $this->createTeamPresentationCommandHandler;
    }
}
