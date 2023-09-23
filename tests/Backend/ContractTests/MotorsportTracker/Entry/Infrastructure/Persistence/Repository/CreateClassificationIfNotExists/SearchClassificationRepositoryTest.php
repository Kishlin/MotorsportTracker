<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists\SearchClassificationRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists\SearchClassificationRepository
 */
final class SearchClassificationRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindAClassificationByEntry(): void
    {
        self::loadFixture('motorsport.result.classification.maxVerstappenAtDutchGP2022Race');

        $repository = new SearchClassificationRepository(self::connection());

        $entryId = self::fixtureId('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race');

        $id = $repository->findForEntry(new UuidValueObject($entryId));

        self::assertNotNull($id);
        self::assertSame(self::fixtureId('motorsport.result.classification.maxVerstappenAtDutchGP2022Race'), $id->value());
    }
}
