<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateStepTypeIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\StepTypeIdForLabelGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class StepTypeIdForLabelRepositoryUsingDoctrine extends CoreRepository implements StepTypeIdForLabelGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function idForLabel(StringValueObject $label): ?UuidValueObject
    {
        $qb = $this->entityManager->createQueryBuilder();

        $query = $qb
            ->select('stepType.id')
            ->from(StepType::class, 'stepType')
            ->where('stepType.label = :label')
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
