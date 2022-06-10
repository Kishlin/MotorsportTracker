<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CountryIdForCodeGateway;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CountryIdForCodeGatewayUsingDoctrine extends DoctrineRepository implements CountryIdForCodeGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function idForCode(CountryCode $code): ?CountryId
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
            /** @var array{id: CountryId} $data */
            $data = $query->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        return $data['id'];
    }
}
