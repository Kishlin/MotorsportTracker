<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists\SessionTypeIdForLabelRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists\SessionTypeIdForLabelRepository
 */
final class SessionTypeIdForLabelRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanRetrieveAnId(): void
    {
        $this->loadFixture('motorsport.event.sessionType.race');

        $repository = new SessionTypeIdForLabelRepository($this->connection());

        $expected = self::fixtureId('motorsport.event.sessionType.race');
        $actual   = $repository->idForLabel(new StringValueObject('race'));

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    public function testItReturnsNullIfLabelDoesNotExist(): void
    {
        $repository = new SessionTypeIdForLabelRepository($this->connection());

        self::assertNull($repository->idForLabel(new StringValueObject('race')));
    }
}
