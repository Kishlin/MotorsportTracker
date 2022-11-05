<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Result;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Gateway\ResultGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class RecordResultsCommandHandler implements CommandHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private UuidGenerator $uuidGenerator,
        private ResultGateway $gateway,
    ) {
    }

    public function __invoke(RecordResultsCommand $command): bool
    {
        foreach ($command->results() as $result) {
            $this->doRecordResult($result, $command);
        }

        $this->eventDispatcher->dispatch(new ResultsRecordedDomainEvent($command->eventStepId()));

        return true;
    }

    private function doRecordResult(ResultDTO $result, RecordResultsCommand $command): void
    {
        $result = Result::create(
            new ResultId($this->uuidGenerator->uuid4()),
            $result->racerId(),
            $command->eventStepId(),
            $result->fastestLapTime(),
            $result->position(),
            $result->points(),
        );

        try {
            $this->gateway->save($result);
        } catch (Throwable $e) {
            throw new ResultCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$result->pullDomainEvents());
    }
}
