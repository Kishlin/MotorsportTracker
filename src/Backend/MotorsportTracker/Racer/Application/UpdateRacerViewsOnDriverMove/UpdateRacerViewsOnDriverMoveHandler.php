<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerViewsOnDriverMove;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Domain\DomainEvent\DriverMoveCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Entity\Racer;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Gateway\RacerGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerCarId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerDriverId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerStartDate;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;

final class UpdateRacerViewsOnDriverMoveHandler implements DomainEventSubscriber
{
    public function __construct(
        private DriverMoveDataGateway $driverMoveDataGateway,
        private ExistingRacerGateway $existingRacerGateway,
        private RacerGateway $racerGateway,
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    /**
     * @throws DriverMoveDataNotFoundException|Exception
     */
    public function __invoke(DriverMoveCreatedDomainEvent $event): void
    {
        $driverMove = $this->driverMoveDataGateway->find($event->aggregateUuid());
        $racer      = $this->existingRacerGateway->findIfExistsForDriverMove($event->aggregateUuid());

        if (null !== $racer) {
            $racer->nowEndsJustBefore($driverMove->date());

            $this->racerGateway->save($racer);
        }

        $newRacer = Racer::create(
            new RacerId($this->uuidGenerator->uuid4()),
            new RacerDriverId($driverMove->driverId()),
            new RacerCarId($driverMove->carId()),
            new RacerStartDate($driverMove->date()),
            new RacerEndDate(
                new DateTimeImmutable(sprintf('%d-12-31 23:59:59', $driverMove->date()->format('Y'))),
            ),
        );

        $this->racerGateway->save($newRacer);
        $this->eventDispatcher->dispatch(...$newRacer->pullDomainEvents());
    }

    public static function subscribedTo(): array
    {
        return [
            DriverMoveCreatedDomainEvent::class,
        ];
    }
}
