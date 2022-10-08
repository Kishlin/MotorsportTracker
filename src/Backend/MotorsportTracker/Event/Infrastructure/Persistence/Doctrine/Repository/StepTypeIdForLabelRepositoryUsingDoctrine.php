<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\StepTypeIdForLabelGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class StepTypeIdForLabelRepositoryUsingDoctrine extends DoctrineRepository implements StepTypeIdForLabelGateway
{
    /**
     * @throws NonUniqueResultException
     */
    public function idForLabel(StepTypeLabel $label): ?StepTypeId
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
            /** @var array{id: StepTypeId} $data */
            $data = $query->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        return $data['id'];
    }
}
