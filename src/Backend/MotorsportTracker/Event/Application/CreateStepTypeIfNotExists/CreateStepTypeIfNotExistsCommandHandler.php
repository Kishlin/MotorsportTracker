<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\StepTypeGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateStepTypeIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly StepTypeIdForLabelGateway $stepTypeIdForLabelGateway,
        private readonly StepTypeGateway $stepTypeGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateStepTypeIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->stepTypeIdForLabelGateway->idForLabel($command->label());

        if (null !== $id) {
            return $id;
        }

        $newId    = new UuidValueObject($this->uuidGenerator->uuid4());
        $stepType = StepType::create($newId, $command->label());

        $this->stepTypeGateway->save($stepType);

        $this->eventDispatcher->dispatch(...$stepType->pullDomainEvents());

        return $stepType->id();
    }
}
