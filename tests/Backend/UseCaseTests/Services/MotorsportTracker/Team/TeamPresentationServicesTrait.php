<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\CreateTeamPresentationCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\Time\Clock;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team\TeamPresentationRepositorySpy;

trait TeamPresentationServicesTrait
{
    private ?TeamPresentationRepositorySpy $teamPresentationRepositorySpy = null;

    private ?CreateTeamPresentationCommandHandler $createTeamPresentationCommandHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function uuidGenerator(): UuidGenerator;

    abstract public function clock(): Clock;

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
