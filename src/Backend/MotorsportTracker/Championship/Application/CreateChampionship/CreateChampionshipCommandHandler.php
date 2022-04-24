<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\ChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class CreateChampionshipCommandHandler implements CommandHandler
{
    public function __construct(
        private UuidGenerator $uuidGenerator,
        private ChampionshipGateway $gateway,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateChampionshipCommand $command): ChampionshipId
    {
        $id = new ChampionshipId($this->uuidGenerator->uuid4());

        $championship = Championship::create($id, $command->name(), $command->slug());

        try {
            $this->gateway->save($championship);
        } catch (Throwable $e) {
            throw new ChampionshipCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$championship->pullDomainEvents());

        return $championship->id();
    }
}
