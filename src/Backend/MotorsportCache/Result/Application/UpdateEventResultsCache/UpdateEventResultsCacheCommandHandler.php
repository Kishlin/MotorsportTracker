<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Event\EventResultsBySessionsCreationFailedEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Event\NoSessionsToComputeEvent;
use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Gateway\SessionClassificationGateway;
use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\Gateway\SessionsToComputeGateway;
use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\MotorsportCache\Result\Domain\ValueObject\ResultsBySession;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;
use Throwable;

final readonly class UpdateEventResultsCacheCommandHandler implements CommandHandler
{
    public function __construct(
        private SessionsToComputeGateway $sessionsToComputeGateway,
        private SessionClassificationGateway $raceResultGateway,
        private EventDispatcher $eventDispatcher,
        private CachePersister $cachePersister,
    ) {
    }

    public function __invoke(UpdateEventResultsCacheCommand $command): void
    {
        $sessionsToCompute = $this->sessionsToComputeGateway->findSessions($command->eventId())->sessions();

        if (empty($sessionsToCompute)) {
            $this->eventDispatcher->dispatch(NoSessionsToComputeEvent::forEvent($command->eventId()));

            return;
        }

        try {
            $this->createEventResultsBySessionsForEvent($sessionsToCompute, $command->eventId());
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(EventResultsBySessionsCreationFailedEvent::withScalars($command->eventId(), $e));
        }
    }

    /**
     * @param array<array{id: string, type: string}> $racesToCompute
     */
    private function createEventResultsBySessionsForEvent(array $racesToCompute, string $eventId): void
    {
        $raceResultList = [];
        foreach ($racesToCompute as $raceToCompute) {
            $raceResult = $this->raceResultGateway->findResult($raceToCompute['id']);

            $raceResultList[] = [
                'session' => [
                    'id'   => $raceToCompute['id'],
                    'type' => $raceToCompute['type'],
                ],
                'result' => $raceResult->results(),
            ];
        }

        $eventResultsByRace = EventResultsByRace::create(ResultsBySession::fromData($raceResultList));

        $this->cachePersister->save($eventResultsByRace, ['eventId' => $eventId]);
    }
}
