<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamPresentation;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationName;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationTeamId;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamPresentation\SaveTeamPresentationRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamPresentation\SaveTeamPresentationRepositoryUsingDoctrine
 */
final class SaveTeamPresentationRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAnEntity(): void
    {
        $id        = '258588ed-f704-4be1-93ce-fddfc301942e';
        $teamId    = 'a5145724-9d7a-42a1-b50a-f5c2349a9bf3';
        $name      = 'Team name';
        $image     = 'https://cdn.example.com/image.webp';
        $createdOn = new DateTimeImmutable();

        $teamPresentation = TeamPresentation::create(
            new TeamPresentationId($id),
            new TeamPresentationTeamId($teamId),
            new TeamPresentationName($name),
            new TeamPresentationImage($image),
            new TeamPresentationCreatedOn($createdOn),
        );

        $repository = new SaveTeamPresentationRepositoryUsingDoctrine(self::entityManager());

        $repository->save($teamPresentation);

        self::assertAggregateRootWasSaved($teamPresentation);
    }
}
