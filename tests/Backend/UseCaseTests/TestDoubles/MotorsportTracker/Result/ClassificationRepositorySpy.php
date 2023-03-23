<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\SaveClassificationGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\SearchClassificationGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;
use RuntimeException;

/**
 * @property Classification[] $objects
 *
 * @method Classification[]    all()
 * @method null|Classification get(UuidValueObject $id)
 * @method Classification      safeGet(UuidValueObject $id)
 */
final class ClassificationRepositorySpy extends AbstractRepositorySpy implements SaveClassificationGateway, SearchClassificationGateway
{
    public function save(Classification $classification): void
    {
        if (null !== $this->findForEntry($classification->entry())) {
            throw new RuntimeException('Already exists.');
        }

        $this->add($classification);
    }

    public function findForEntry(UuidValueObject $entry): ?UuidValueObject
    {
        foreach ($this->objects as $savedClassification) {
            if ($savedClassification->entry()->equals($entry)) {
                return $savedClassification->id();
            }
        }

        return null;
    }
}
