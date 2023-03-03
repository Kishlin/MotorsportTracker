<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueViewer;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchVenueViewerUsingDoctrine extends CoreRepository implements SearchVenueViewer
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function search(string $keyword): ?VenueId
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('v.id')
            ->from('venues', 'v')
            ->where("LOWER(REPLACE(v.name, ' ', '')) LIKE LOWER(REPLACE(:name, ' ', ''))")
            ->setParameter('name', "%{$keyword}%")
        ;

        /** @var array<array{id: string}> $result */
        $result = $qb->executeQuery()->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new NonUniqueResultException();
        }

        return new VenueId($result[0]['id']);
    }
}
