<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository\ComputeSeasonStandings;

use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\Gateway\StandingsDataGateway;
use Kishlin\Backend\MotorsportCache\Standing\Application\ComputeSeasonStandings\StandingsDataDTO;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class StandingsDataRepository extends CoreRepository implements StandingsDataGateway
{
    private const COLOR_SELECT = <<<'TXT'
case when t is not null
    then t.color
end
TXT;

    private const COUNTRY_SELECT = <<<'TXT'
case when co is not null
    then json_build_object(
        'id', co.id,
        'code', co.code,
        'name', co.name
    )
end
TXT;

    public function findForSeason(string $championship, int $year): StandingsDataDTO
    {
        return StandingsDataDTO::fromStandings(
            $this->constructorStandings($championship, $year),
            $this->teamStandings($championship, $year),
            $this->driverStandings($championship, $year),
        );
    }

    /**
     * @return array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }>
     */
    private function constructorStandings(string $championship, int $year): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('sc.id', 'id')
            ->addSelect('sc.series_class', 'series_class')
            ->addSelect('c.name', 'name')
            ->addSelect('sc.position', 'position')
            ->addSelect('sc.points', 'points')
            ->addSelect(self::COLOR_SELECT, 'color')
            ->addSelect(self::COUNTRY_SELECT, 'country')
            ->from('standing_constructor', 'sc')
            ->innerJoin('season', 's', $qb->expr()->eq('sc.season', 's.id'))
            ->innerJoin('championship', 'ch', $qb->expr()->eq('ch.id', 's.championship'))
            ->innerJoin('constructor', 'c', $qb->expr()->eq('c.id', 'sc.standee'))
            ->leftJoin('constructor_team', 'ct', $qb->expr()->eq('c.id', 'ct.constructor'))
            ->leftJoin('team', 't', $qb->expr()->eq('t.id', 'ct.team'))
            ->leftJoin('country', 'co', $qb->expr()->eq('co.id', 't.country'))
            ->where($qb->expr()->eq('ch.name', ':championship'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->orderBy('sc.position')
            ->withParam('championship', $championship)
            ->withParam('year', $year)
        ;

        /**
         * @var array<array{
         *     id: string,
         *     series_class: string,
         *     name: string,
         *     position: int,
         *     points: float,
         *     color: null|string,
         *     country: null|string,
         * }> $constructorStandings
         */
        $constructorStandings = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        assert(is_array($constructorStandings));

        return $constructorStandings;
    }

    /**
     * @return array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: string,
     *     country: null|string,
     * }>
     */
    private function teamStandings(string $championship, int $year): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('st.id', 'id')
            ->addSelect('st.series_class', 'series_class')
            ->addSelect('t.name', 'name')
            ->addSelect('st.position', 'position')
            ->addSelect('st.points', 'points')
            ->addSelect('t.color', 'color')
            ->addSelect(self::COUNTRY_SELECT, 'country')
            ->from('standing_team', 'st')
            ->innerJoin('season', 's', $qb->expr()->eq('st.season', 's.id'))
            ->innerJoin('championship', 'ch', $qb->expr()->eq('ch.id', 's.championship'))
            ->leftJoin('team', 't', $qb->expr()->eq('t.id', 'st.standee'))
            ->leftJoin('country', 'co', $qb->expr()->eq('co.id', 't.country'))
            ->where($qb->expr()->eq('ch.name', ':championship'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->orderBy('st.position')
            ->withParam('championship', $championship)
            ->withParam('year', $year)
        ;

        /**
         * @var array<array{
         *     id: string,
         *     series_class: string,
         *     name: string,
         *     position: int,
         *     points: float,
         *     color: string,
         *     country: null|string,
         * }> $teamStandings
         */
        $teamStandings = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        assert(is_array($teamStandings));

        return $teamStandings;
    }

    /**
     * @return array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     short_code: null|string,
     *     position: int,
     *     points: float,
     *     color: null|string,
     *     country: null|string,
     * }>
     */
    private function driverStandings(string $championship, int $year): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('sd.id', 'id')
            ->addSelect('sd.series_class', 'series_class')
            ->addSelect('d.name', 'name')
            ->addSelect('d.short_code', 'short_code')
            ->addSelect('sd.position', 'position')
            ->addSelect('sd.points', 'points')
//            ->addSelect('d.color', 'color')
            ->addSelect(self::COUNTRY_SELECT, 'country')
            ->from('standing_driver', 'sd')
            ->innerJoin('season', 's', $qb->expr()->eq('sd.season', 's.id'))
            ->innerJoin('championship', 'ch', $qb->expr()->eq('ch.id', 's.championship'))
            ->leftJoin('driver', 'd', $qb->expr()->eq('d.id', 'sd.standee'))
            ->leftJoin('country', 'co', $qb->expr()->eq('co.id', 'd.country'))
            ->where($qb->expr()->eq('ch.name', ':championship'))
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->orderBy('sd.position')
            ->withParam('championship', $championship)
            ->withParam('year', $year)
        ;

        /**
         * @var array<array{
         *     id: string,
         *     series_class: string,
         *     short_code: null|string,
         *     name: string,
         *     position: int,
         *     points: float,
         *     color: null|string,
         *     country: null|string,
         * }> $driverStandings
         */
        $driverStandings = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        assert(is_array($driverStandings));

        return $driverStandings;
    }
}
