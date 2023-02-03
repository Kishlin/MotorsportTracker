<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\Time\Clock;

final class CreateChampionshipPresentationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveChampionshipPresentationGateway $gateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $dispatcher,
        private readonly Clock $clock,
    ) {
    }

    public function __invoke(CreateChampionshipPresentationCommand $command): ChampionshipPresentationId
    {
        $id        = new ChampionshipPresentationId($this->uuidGenerator->uuid4());
        $createdOn = new ChampionshipPresentationCreatedOn($this->clock->now());

        $championshipPresentation = ChampionshipPresentation::create(
            $id,
            $command->championshipId(),
            $command->icon(),
            $command->color(),
            $createdOn,
        );

        $this->gateway->save($championshipPresentation);

        $this->dispatcher->dispatch(...$championshipPresentation->pullDomainEvents());

        return $id;
    }
}
