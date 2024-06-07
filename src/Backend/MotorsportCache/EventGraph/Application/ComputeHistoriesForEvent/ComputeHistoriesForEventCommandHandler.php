<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent;

use JsonException;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommandHandler;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\GraphDataSaverUsingCacheItem;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Histories;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\RaceAndSprintSessionsGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\HistoriesValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final class ComputeHistoriesForEventCommandHandler extends ComputeGraphCommandHandler
{
    public function __construct(
        private readonly GraphDataSaverUsingCacheItem $graphDataSaverUsingCacheItem,
        private readonly RaceAndSprintSessionsGateway $raceSessionsGateway,
        private readonly HistoriesDataGateway $historiesDataGateway,
        private readonly EventDispatcher $eventDispatcher,
    ) {
        parent::__construct(
            $this->raceSessionsGateway,
            $this->graphDataSaverUsingCacheItem,
            $this->eventDispatcher,
        );
    }

    public function __invoke(ComputeHistoriesForEventCommand $command): void
    {
        parent::doInvoke($command);
    }

    /**
     * @throws JsonException
     */
    protected function computeDataForSession(array $session): array
    {
        $histories = $this->historiesDataGateway->findForSession($session['session'])->data();

        if (empty($histories)) {
            $this->eventDispatcher->dispatch(EmptyHistoriesDataEvent::forSession($session['session']));

            return [];
        }

        $seriesList = [];
        $laps       = 0;

        foreach ($histories as $history) {
            /** @var array<int, array{lap: int, position: int, pit: bool}> $lapsList */
            $lapsList = json_decode($history['laps'], true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($lapsList));

            $positions = $pits = [];
            foreach ($lapsList as $lap) {
                if (true === $lap['pit']) {
                    $pits[$lap['lap']] = true;
                }

                $positions[$lap['lap']] = $lap['position'];
            }

            $laps = max($laps, count($positions));

            $seriesList[] = [
                'color'      => $history['color'],
                'index'      => $this->nextIndexForColor($history['color']),
                'car_number' => $history['car_number'],
                'short_code' => $history['short_code'],
                'positions'  => $positions,
                'pits'       => $pits,
            ];
        }

        return [
            'session' => [
                'id'   => $session['session'],
                'type' => $session['type'],
            ],
            'laps'   => $laps,
            'series' => $seriesList,
        ];
    }

    /**
     * @param array{
     *     session: array{id: string, type: string},
     *     laps: int,
     *     series: array<array{
     *         color: string,
     *         index: int,
     *         car_number: string,
     *         short_code: string,
     *         positions: array<int, int>,
     *         pits: array<int, bool>,
     *     }>
     * } $dataPerSession
     */
    protected function createGraph(array $dataPerSession): Graph
    {
        return Histories::create(HistoriesValueObject::with($dataPerSession));
    }
}
