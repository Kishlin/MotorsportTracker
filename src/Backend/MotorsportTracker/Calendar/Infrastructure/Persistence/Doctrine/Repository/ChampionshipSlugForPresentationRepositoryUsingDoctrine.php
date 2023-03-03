<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\ChampionshipSlugForPresentationGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class ChampionshipSlugForPresentationRepositoryUsingDoctrine extends CoreRepository implements ChampionshipSlugForPresentationGateway
{
    /**
     * @throws Exception
     */
    public function findChampionshipSlugForPresentationId(UuidValueObject $presentationId): string
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('c.slug')
            ->from('championships', 'c')
            ->leftJoin('c', 'championship_presentations', 'cp', 'c.id = cp.championship')
            ->where('cp.id = :id')
            ->setParameter('id', $presentationId->value())
        ;

        /** @var string $result */
        $result = $qb->executeQuery()->fetchOne();

        assert(is_string($result));

        return $result;
    }
}
