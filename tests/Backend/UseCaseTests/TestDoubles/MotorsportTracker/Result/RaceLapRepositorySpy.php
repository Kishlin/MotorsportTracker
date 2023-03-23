<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Result;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\SaveRaceLapGateway;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\SearchRaceLapGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;
use RuntimeException;

/**
 * @property RaceLap[] $objects
 *
 * @method RaceLap[]    all()
 * @method null|RaceLap get(UuidValueObject $id)
 * @method RaceLap      safeGet(UuidValueObject $id)
 */
final class RaceLapRepositorySpy extends AbstractRepositorySpy implements SaveRaceLapGateway, SearchRaceLapGateway
{
    public function save(RaceLap $raceLap): void
    {
        if (null !== $this->findForEntryAndLap($raceLap->entry(), $raceLap->lap())) {
            throw new RuntimeException('Already exists.');
        }

        $this->add($raceLap);
    }

    public function findForEntryAndLap(UuidValueObject $entry, StrictlyPositiveIntValueObject $lap): ?UuidValueObject
    {
        foreach ($this->objects as $raceLap) {
            if ($raceLap->entry()->equals($entry) && $raceLap->lap()->equals($lap)) {
                return $raceLap->id();
            }
        }

        return null;
    }
}
