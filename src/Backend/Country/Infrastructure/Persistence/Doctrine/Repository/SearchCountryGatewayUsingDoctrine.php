<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\SearchCountryGateway;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SearchCountryGatewayUsingDoctrine extends CoreRepository implements SearchCountryGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function searchForCode(StringValueObject $code): ?UuidValueObject
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('country.id')
            ->from(Country::class, 'country')
            ->where('country.code = :code')
            ->setParameter('code', $code->value())
            ->getQuery()
        ;

        try {
            /** @var array{id: UuidValueObject} $data */
            $data = $query->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        return $data['id'];
    }
}
