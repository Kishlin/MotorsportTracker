<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApply;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApplyGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApplyNotFoundException;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class PresentationToApplyRepositoryUsingDoctrine extends CoreRepository implements PresentationToApplyGateway
{
    /**
     * @throws Exception
     */
    public function findData(UuidValueObject $presentationId): PresentationToApply
    {
        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $qb->select('cp.color, cp.icon')
            ->from('championship_presentations', 'cp')
            ->where('cp.id = :id')
            ->setParameter('id', $presentationId->value())
        ;

        /** @var array{color: string, icon: string}|false $result */
        $result = $qb->executeQuery()->fetchAssociative();

        if (false === $result) {
            throw new PresentationToApplyNotFoundException();
        }

        return PresentationToApply::fromData($result);
    }
}
