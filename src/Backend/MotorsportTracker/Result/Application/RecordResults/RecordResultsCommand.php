<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultEventStepId;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class RecordResultsCommand implements Command
{
    /** @param ResultDTO[] $results */
    private function __construct(
        private string $eventStepId,
        private array $results,
    ) {
    }

    public function eventStepId(): ResultEventStepId
    {
        return new ResultEventStepId($this->eventStepId);
    }

    /** @return ResultDTO[] */
    public function results(): array
    {
        return $this->results;
    }

    /**
     * @param ResultDTO[] $resultDTOs
     */
    public static function fromScalars(string $eventStepId, array $resultDTOs): self
    {
        return new self($eventStepId, $resultDTOs);
    }
}
