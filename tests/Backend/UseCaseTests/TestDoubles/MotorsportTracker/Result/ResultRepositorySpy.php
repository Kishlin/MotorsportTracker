<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\RecordResults\ResultCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Result;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Gateway\ResultGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Result[] $objects
 *
 * @method Result get(ResultId $id)
 */
final class ResultRepositorySpy extends AbstractRepositorySpy implements ResultGateway
{
    public function save(Result $result): void
    {
        if ($this->resultIsADuplicate($result)) {
            throw new ResultCreationFailureException();
        }

        $this->objects[$result->id()->value()] = $result;
    }

    private function resultIsADuplicate(Result $result): bool
    {
        foreach ($this->objects as $storedResult) {
            if ($storedResult->eventStepId()->equals($result->eventStepId())
                && (
                    $storedResult->position()->equals($result->position())
                    || $storedResult->racerId()->equals($result->racerId())
                )) {
                return true;
            }
        }

        return false;
    }
}
