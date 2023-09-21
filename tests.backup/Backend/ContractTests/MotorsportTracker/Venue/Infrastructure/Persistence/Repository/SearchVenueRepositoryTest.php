<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Venue\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Repository\SearchVenueRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Repository\SearchVenueRepository
 */
final class SearchVenueRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindAVenue(): void
    {
        $this->loadFixture('motorsport.venue.venue.zandvoort');

        $repository = new SearchVenueRepository(self::connection());

        self::assertSame(
            $this->fixtureId('motorsport.venue.venue.zandvoort'),
            $repository->search(new StringValueObject('Circuit Zandvoort'))?->value(),
        );
    }

    public function testItIsNullWhenThereIsNoResult(): void
    {
        $repository = new SearchVenueRepository(self::connection());

        self::assertNull($repository->search(new StringValueObject('Circuit Zandvoort')));
    }
}
