<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Infrastructure\Persistence\UpdateTeamAnalyticsCache;

use JsonException;
use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateTeamAnalyticsCache\TeamAnalyticsForSeasonGateway;
use Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO\AnalyticsForSeasonDTO;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class TeamAnalyticsForSeasonRepository extends CoreRepository implements TeamAnalyticsForSeasonGateway
{
    private const COUNTRY_SELECT = <<<'TXT'
case when c is not null
    then json_build_object(
        'id', c.id,
        'code', c.code,
        'name', c.name
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
            ->addSelect('t.name')
            ->addSelect('a.position')
            ->addSelect('a.points')
            ->addSelect('a.class_wins')
            ->addSelect('a.fastest_laps')
            ->addSelect('a.final_appearances')
            ->addSelect('a.finishes_one_and_two')
            ->addSelect('a.podiums')
            ->addSelect('a.poles')
            ->addSelect('a.qualifies_one_and_two')
            ->addSelect('a.races_led')
            ->addSelect('a.rallies_led')
            ->addSelect('a.retirements')
            ->addSelect('a.semi_final_appearances')
            ->addSelect('a.stage_wins')
            ->addSelect('a.starts')
            ->addSelect('a.top10s')
            ->addSelect('a.top5s')
            ->addSelect('a.wins')
            ->addSelect('a.wins_percentage')
            ->from('analytics_teams', 'a')
            ->innerJoin('team', 't', $qb->expr()->eq('a.team', 't.id'))
            ->leftJoin('country', 'c', $qb->expr()->eq('a.country', 'c.id'))
            ->innerJoin('season', 's', $qb->expr()->eq('a.season', 's.id'))
            ->innerJoin('championship', 'ch', $qb->expr()->eq('s.championship', 'ch.id'))
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
