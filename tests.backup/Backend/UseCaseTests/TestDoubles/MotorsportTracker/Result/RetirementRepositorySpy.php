<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\SaveRetirementGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\SearchRetirementGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;
use RuntimeException;

/**
 * @property Retirement[] $objects
 *
 * @method Retirement[]    all()
 * @method null|Retirement get(UuidValueObject $id)
 * @method Retirement      safeGet(UuidValueObject $id)
 */
final class RetirementRepositorySpy extends AbstractRepositorySpy implements SaveRetirementGateway, SearchRetirementGateway
{
    public function save(Retirement $retirement): void
    {
        if (null !== $this->findForEntry($retirement->entry())) {
            throw new RuntimeException('Already exists.');
        }

        $this->add($retirement);
    }

    public function findForEntry(UuidValueObject $entry): ?UuidValueObject
    {
        foreach ($this->objects as $savedRetirement) {
            if ($savedRetirement->entry()->equals($entry)) {
                return $savedRetirement->id();
            }
        }

        return null;
    }
}
