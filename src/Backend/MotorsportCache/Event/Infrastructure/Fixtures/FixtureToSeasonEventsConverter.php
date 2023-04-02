<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Fixtures;

use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\SeasonEvents;
use Kishlin\Backend\MotorsportCache\Event\Domain\ValueObject\SeasonEventList;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToSeasonEventsConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return SeasonEvents::instance(
            new UuidValueObject($fixture->identifier()),
            new StringValueObject($fixture->getString('championship')),
            new StrictlyPositiveIntValueObject($fixture->getInt('year')),
            SeasonEventList::fromData($this->decodeEvents($fixture->getString('events'))),
        );
    }

    /**
     * @return array<string, array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     index: int,
     * }>
     */
    private function decodeEvents(string $data): array
    {
        /**
         * @var array<string, array{
         *     id: string,
         *     name: string,
         *     slug: string,
         *     index: int,
         * }> $decoded
         */
        $decoded = json_decode($data, true);
        assert(is_array($decoded));

        return $decoded;
    }
}
