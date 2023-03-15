<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Doctrine\Repository\SyncCalendarEvents;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\FindSeriesRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncCalendarEvents\FindSeriesRepositoryUsingDoctrine
 */
final class FindSeriesRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanFindData(): void
    {
        self::loadFixtures(
            'motorsport.championship.championshipPresentation.formulaOneWhite',
            'motorsport.championship.season.formulaOne2022',
        );

        $repository = new FindSeriesRepositoryUsingDoctrine(self::entityManager());

        $series = $repository->findForSlug(new StringValueObject('formula1'), new PositiveIntValueObject(2022));

        self::assertNotNull($series);

        self::assertSame('Formula One', $series->data()['name']);
        self::assertSame('formula1', $series->data()['slug']);
        self::assertSame(2022, $series->data()['year']);
        self::assertSame('#fff', $series->data()['color']);
        self::assertSame('f1.png', $series->data()['icon']);
    }

    /**
     * @throws Exception
     */
    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new FindSeriesRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->findForSlug(new StringValueObject('not-exist'), new PositiveIntValueObject(2022)));
    }
}
