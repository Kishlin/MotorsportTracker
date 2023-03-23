<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\TyreDetailsValueObject;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToRaceLapConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return RaceLap::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('entry')),
            new StrictlyPositiveIntValueObject($fixture->getInt('lap')),
            new StrictlyPositiveIntValueObject($fixture->getInt('position')),
            new BoolValueObject($fixture->getBool('pit')),
            new StrictlyPositiveIntValueObject($fixture->getInt('time')),
            new StrictlyPositiveIntValueObject($fixture->getInt('timeToLead')),
            new PositiveIntValueObject($fixture->getInt('lapsToLead')),
            new StrictlyPositiveIntValueObject($fixture->getInt('timeToNext')),
            new PositiveIntValueObject($fixture->getInt('lapsToNext')),
            new TyreDetailsValueObject($this->decodeTypeDetails($fixture->getString('tyreDetails'))),
        );
    }

    /**
     * @return array{
     *     type: string,
     *     wear: string,
     *     laps: int,
     * }[]
     */
    private function decodeTypeDetails(string $data): array
    {
        /**
         * @var array{
         *     type: string,
         *     wear: string,
         *     laps: int,
         * }[] $decoded
         */
        $decoded = json_decode($data, true);
        assert(is_array($decoded));

        return $decoded;
    }
}
