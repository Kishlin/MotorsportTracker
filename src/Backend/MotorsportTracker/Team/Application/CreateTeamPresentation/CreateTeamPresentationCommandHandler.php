<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\Time\Clock;

final class CreateTeamPresentationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveTeamPresentationGateway $gateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
        private readonly Clock $clock,
    ) {
    }

    public function __invoke(CreateTeamPresentationCommand $command): TeamPresentationId
    {
        $teamPresentation = TeamPresentation::create(
            new TeamPresentationId($this->uuidGenerator->uuid4()),
            $command->teamId(),
            $command->name(),
            $command->image(),
            new TeamPresentationCreatedOn($this->clock->now()),
        );

        $this->eventDispatcher->dispatch(...$teamPresentation->pullDomainEvents());

        $this->gateway->save($teamPresentation);

        return $teamPresentation->id();
    }
}
