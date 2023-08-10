<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\TyreDetailsValueObject;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
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
            new PositiveIntValueObject($fixture->getInt('lap')),
            new PositiveIntValueObject($fixture->getInt('position')),
            new BoolValueObject($fixture->getBool('pit')),
            new PositiveIntValueObject($fixture->getInt('time')),
            new NullableIntValueObject($fixture->getInt('timeToLead')),
            new NullableIntValueObject($fixture->getInt('lapsToLead')),
            new NullableIntValueObject($fixture->getInt('timeToNext')),
            new NullableIntValueObject($fixture->getInt('lapsToNext')),
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
