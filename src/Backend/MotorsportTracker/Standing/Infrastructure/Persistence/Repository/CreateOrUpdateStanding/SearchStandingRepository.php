<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateOrUpdateStanding;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\SearchStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchStandingRepository extends CoreRepository implements SearchStandingGateway
{
    public function findForSeasonClassAndDriver(
        UuidValueObject $season,
        NullableStringValueObject $seriesClass,
        UuidValueObject $standee,
        StandingType $standingType,
    ): ?UuidValueObject {
        $qb = $this->connection->createQueryBuilder();

        $table = 'standing_' . $standingType->toString();

        $qb->select('sd.id')
            ->from($table, 'sd')
            ->where($qb->expr()->eq('sd.season', ':season'))
            ->andWhere($qb->expr()->eq('sd.standee', ':standee'))
            ->withParam('season', $season->value())
            ->withParam('standee', $standee->value())
        ;

        if (null === $seriesClass->value()) {
            $qb->andWhere('sd.series_class is null');
        } else {
            $qb
                ->andWhere($qb->expr()->eq('sd.series_class', ':seriesClass'))
                ->withParam('seriesClass', $seriesClass->value())
            ;
        }

        /** @var array<array{id: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return new UuidValueObject($result[0]['id']);
    }
}
