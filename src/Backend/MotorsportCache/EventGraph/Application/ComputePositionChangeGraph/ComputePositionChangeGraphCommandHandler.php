<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommandHandler;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\GraphDataSaverUsingEntity;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\EventGraph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\RaceAndSprintSessionsGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\EventGraphDataValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ComputePositionChangeGraphCommandHandler extends ComputeGraphCommandHandler
{
    private ComputePositionChangeGraphCommand $command;

    public function __construct(
        private readonly GraphDataSaverUsingEntity $graphDataSaverUsingEntity,
        private readonly RaceAndSprintSessionsGateway $eventRaceSessionsGateway,
        private readonly PositionChangeDataGateway $positionChangeDataGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
        parent::__construct(
            $this->eventRaceSessionsGateway,
            $this->graphDataSaverUsingEntity,
            $this->eventDispatcher,
        );
    }

    public function __invoke(ComputePositionChangeGraphCommand $command): void
    {
        $this->command = $command;

        parent::doInvoke($command);
    }

    protected function computeDataForSession(array $session): array
    {
        $positionChangeData = $this->positionChangeDataGateway->findForSession($session['session']);

        if (empty($positionChangeData->data())) {
            $this->eventDispatcher->dispatch(EmptyPositionChangeDataEvent::forSession($session['session']));

            return [];
        }

        return $this->buildGraphDataForSession($session, $positionChangeData);
    }

    protected function createGraph(array $dataPerSession): Graph
    {
        return EventGraph::positionChange(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            new UuidValueObject($this->command->eventId()),
            new EventGraphDataValueObject($dataPerSession),
        );
    }

    /**
     * @param array{session: string, type: string} $session
     *
     * @return array<string, mixed>
     */
    private function buildGraphDataForSession(array $session, PositionChangeData $positionChangeData): array
    {
        if (empty($positionChangeData->data())) {
            return [];
        }

        $seriesList = [];
        $minChanges = $maxChanges = 0;

        foreach ($positionChangeData->data() as $series) {
            $changes = $series['changes'];

            $minChanges = min($changes, $minChanges);
            $maxChanges = max($changes, $maxChanges);

            $seriesList[] = [
                'color'      => $series['color'],
                'index'      => $this->nextIndexForColor($series['color']),
                'car_number' => $series['car_number'],
                'short_code' => $series['short_code'],
                'changes'    => $changes,
                'grid'       => $series['grid'],
                'finish'     => $series['finish'],
            ];
        }

        return [
            'session' => [
                'id'   => $session['session'],
                'type' => $session['type'],
            ],
            'minChanges' => $minChanges,
            'maxChanges' => $maxChanges,
            'series'     => $seriesList,
        ];
    }
}
