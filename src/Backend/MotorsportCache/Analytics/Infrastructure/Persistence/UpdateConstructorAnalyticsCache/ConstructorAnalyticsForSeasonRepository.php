<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Infrastructure\Persistence\UpdateConstructorAnalyticsCache;

use JsonException;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\ConstructorAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class ConstructorAnalyticsForSeasonRepository extends CoreRepository implements ConstructorAnalyticsForSeasonGateway
{
    private const COUNTRY_SELECT = <<<'TXT'
case when co is not null
    then json_build_object(
        'id', co.id,
        'code', co.code,
        'name', co.name
    )
end
TXT;

    /**
     * @throws JsonException
     */
    public function find(string $championship, int $year): AnalyticsForSeasonDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('a.id')
            ->addSelect(self::COUNTRY_SELECT, 'country')
            ->addSelect('c.name')
            ->addSelect('a.position')
            ->addSelect('a.points')
            ->addSelect('a.wins')
            ->from('analytics_constructors', 'a')
            ->innerJoin('constructor', 'c', $qb->expr()->eq('a.constructor', 'c.id'))
            ->leftJoin('country', 'co', $qb->expr()->eq('a.country', 'co.id'))
            ->innerJoin('season', 's', $qb->expr()->eq('a.season', 's.id'))
            ->innerJoin('series', 'ch', $qb->expr()->eq('s.series', 'ch.id'))
            ->where($qb->expr()->eq('ch.name', ':championship'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->withParam('championship', $championship)
            ->withParam('year', $year)
            ->orderBy('(a.position+999)%1000')
        ;

        /** @var array<int, array<string, null|array<string, string>|float|int|string>> $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        assert(is_array($ret));

        return AnalyticsForSeasonDTO::fromData($ret);
    }
}
