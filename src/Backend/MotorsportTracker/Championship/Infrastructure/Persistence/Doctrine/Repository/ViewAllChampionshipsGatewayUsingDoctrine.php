<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ChampionshipPOPO;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class ViewAllChampionshipsGatewayUsingDoctrine extends CoreRepository implements ViewAllChampionshipsGateway
{
    public function viewAllChampionships(): array
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('id, name')
            ->from('championships', 'c')
        ;

        /** @var array<array{id: string, name: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        return array_map([$this, 'resultToChampionshipPOPO'], $result);
    }

    /**
     * @param array{id: string, name: string} $line
     */
    private static function resultToChampionshipPOPO(array $line): ChampionshipPOPO
    {
        return ChampionshipPOPO::fromData($line);
    }
}
