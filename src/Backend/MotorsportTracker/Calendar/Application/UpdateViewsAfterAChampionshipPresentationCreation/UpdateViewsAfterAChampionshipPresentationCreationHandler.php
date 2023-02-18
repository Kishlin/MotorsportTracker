<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipPresentationCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEventSubscriber;

final class UpdateViewsAfterAChampionshipPresentationCreationHandler implements DomainEventSubscriber
{
    public function __construct(
        private readonly CalendarViewsToUpdateGateway $calendarViewsToUpdateGateway,
        private readonly PresentationToApplyGateway $presentationToApplyGateway,
        private readonly NewPresentationApplierGateway $newPresentationApplierGateway,
    ) {
    }

    public function __invoke(ChampionshipPresentationCreatedDomainEvent $event): void
    {
        $championshipPresentationId = $event->aggregateUuid();

        $viewsToUpdate = $this->calendarViewsToUpdateGateway->findForPresentation($championshipPresentationId);

        if (empty($viewsToUpdate->idList())) {
            return;
        }

        $presentationToApply = $this->presentationToApplyGateway->findData($championshipPresentationId);

        $this->newPresentationApplierGateway->applyDataToViews($viewsToUpdate, $presentationToApply);
    }

    public static function subscribedTo(): array
    {
        return [
            ChampionshipPresentationCreatedDomainEvent::class,
        ];
    }
}
