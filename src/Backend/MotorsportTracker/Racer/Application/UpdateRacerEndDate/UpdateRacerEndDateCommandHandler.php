<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\Gateway\RacerGateway;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateRacerEndDateCommandHandler implements CommandHandler
{
    public function __construct(
        private FindRacerGateway $findGateway,
        private RacerGateway $saveGateway,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(UpdateRacerEndDateCommand $command): RacerId
    {
        $racer = $this->findGateway->find($command->driverId(), $command->championship(), $command->newEndDate());

        if (null === $racer) {
            throw new RacerNotFoundException();
        }

        $racer->nowEndsJustBefore(new DateTimeImmutable($command->newEndDate()));

        $this->saveGateway->save($racer);

        return $racer->id();
    }
}
