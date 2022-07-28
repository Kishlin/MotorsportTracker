<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\DriverMove;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway\DriverMoveGateway;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class RecordDriverMoveCommandHandler implements CommandHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
        private DriverMoveGateway $gateway,
    ) {
    }

    public function __invoke(RecordDriverMoveCommand $command): DriverMoveId
    {
        $id = new DriverMoveId($this->uuidGenerator->uuid4());

        $driverMove = DriverMove::create($id, $command->driverId(), $command->carId(), $command->date());

        try {
            $this->gateway->save($driverMove);
        } catch (Throwable $e) {
            throw new DriverMoveRecordingFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$driverMove->pullDomainEvents());

        return $id;
    }
}
