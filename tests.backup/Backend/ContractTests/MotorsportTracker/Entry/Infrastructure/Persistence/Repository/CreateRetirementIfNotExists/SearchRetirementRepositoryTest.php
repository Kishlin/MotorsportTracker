<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists\SearchRetirementRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists\SearchRetirementRepository
 */
final class SearchRetirementRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindARetirementByEntry(): void
    {
        self::loadFixture('motorsport.result.retirement.maxVerstappenAtAustralianGP2022Race');

        $repository = new SearchRetirementRepository(self::connection());

        $entryId = self::fixtureId('motorsport.result.entry.maxVerstappenForRedBullRacingAtAustralianGP2022Race');

        $id = $repository->findForEntry(new UuidValueObject($entryId));

        self::assertNotNull($id);
        self::assertSame(self::fixtureId('motorsport.result.retirement.maxVerstappenAtAustralianGP2022Race'), $id->value());
    }
}
