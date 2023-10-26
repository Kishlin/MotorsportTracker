<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\RecordJob;

use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\SaveJobGateway;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\Time\Clock;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class RecordJobCommandHandler implements CommandHandler
{
    public function __construct(
        private SaveJobGateway $saveJobGateway,
        private UuidGenerator $uuidGenerator,
        private Clock $clock,
    ) {
    }

    public function __invoke(RecordJobCommand $command): UuidValueObject
    {
        $uuid = new UuidValueObject($this->uuidGenerator->uuid4());

        $job = Job::new($uuid, $this->clock->now(), $command->type(), $command->params());

        $this->saveJobGateway->save($job);

        return $uuid;
    }
}
