<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\RegisterCar;

use Kishlin\Backend\MotorsportTracker\Car\Domain\Entity\Car;
use Kishlin\Backend\MotorsportTracker\Car\Domain\Gateway\CarGateway;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\CarId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class RegisterCarCommandHandler implements CommandHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
        private CarGateway $gateway,
    ) {
    }

    public function __invoke(RegisterCarCommand $command): CarId
    {
        $carId = new CarId($this->uuidGenerator->uuid4());

        $car = Car::create($carId, $command->teamId(), $command->seasonId(), $command->carNumber());

        try {
            $this->gateway->save($car);
        } catch (Throwable $e) {
            throw new CarRegistrationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$car->pullDomainEvents());

        return $car->id();
    }
}
