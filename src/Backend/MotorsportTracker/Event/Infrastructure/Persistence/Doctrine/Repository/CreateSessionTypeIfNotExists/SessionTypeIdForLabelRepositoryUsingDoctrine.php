<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateSessionTypeIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\SessionTypeIdForLabelGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SessionTypeIdForLabelRepositoryUsingDoctrine extends CoreRepository implements SessionTypeIdForLabelGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function idForLabel(StringValueObject $label): ?UuidValueObject
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('sessionType.id')
            ->from(SessionType::class, 'sessionType')
            ->where('sessionType.label = :label')
            ->setParameter('label', $label->value())
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
