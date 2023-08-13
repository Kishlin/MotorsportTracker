<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

use JsonException;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommandHandler;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Event\EmptyLapByLapDataEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway\LapByLapDataGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\GraphDataSaverUsingEntity;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\EventGraph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\RaceAndSprintSessionsGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\EventGraphDataValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ComputeLapByLapGraphCommandHandler extends ComputeGraphCommandHandler
{
    private const BASE_MAX_TIME_RATIO = 1.07;

    private ComputeLapByLapGraphCommand $command;

    public function __construct(
        private readonly GraphDataSaverUsingEntity $graphDataSaverUsingEntity,
        private readonly RaceAndSprintSessionsGateway $eventRaceSessionsGateway,
        private readonly LapByLapDataGateway $lapByLapDataGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
        parent::__construct(
            $this->eventRaceSessionsGateway,
            $this->graphDataSaverUsingEntity,
            $this->eventDispatcher,
        );
    }

    public function __invoke(ComputeLapByLapGraphCommand $command): void
    {
        $this->command = $command;

        parent::doInvoke($command);
    }

    /**
     * @throws JsonException
     */
    protected function computeDataForSession(array $session): array
    {
        $maxTimeRatio = $this->command->maxTimeRatio() ?? self::BASE_MAX_TIME_RATIO;

        $lapByLapData = $this->lapByLapDataGateway->findForSession($session['session'], $maxTimeRatio);

        if (empty($lapByLapData->data())) {
            $this->eventDispatcher->dispatch(EmptyLapByLapDataEvent::forSession($session['session']));

            return [];
        }

        return $this->buildGraphDataForSession($session, $lapByLapData);
    }

    protected function createGraph(array $dataPerSession): Graph
    {
        return EventGraph::lapByLap(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            new UuidValueObject($this->command->eventId()),
            new EventGraphDataValueObject($dataPerSession),
        );
    }

    /**
     * @param array{session: string, type: string} $session
     *
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    private function buildGraphDataForSession(array $session, LapByLapData $lapByLapData): array
    {
        $seriesList = [];

        $slowest = $laps = 0;
        $fastest = PHP_INT_MAX;

        foreach ($lapByLapData->data() as $series) {
            /** @var array<int, array{lap: int, pit: boolean, time: int}> $lapsList */
            $lapsList = json_decode($series['laps'], true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($lapsList));

            $max = $series['max'];
            assert(is_string($max));

            $lapTimes = $this->computeLapTimes($lapsList, $max);

            if (false === empty($lapTimes)) {
                $fastest = min($fastest, min($lapTimes));
                $slowest = max($slowest, max($lapTimes));
            }

            $laps = max($laps, count($lapTimes));

            $seriesList[] = [
                'color'      => $series['color'],
                'index'      => $this->nextIndexForColor($series['color']),
                'car_number' => $series['car_number'],
                'short_code' => $series['short_code'],
                'lapTimes'   => $lapTimes,
            ];
        }

        return [
            'session' => [
                'id'   => $session['session'],
                'type' => $session['type'],
            ],
            'laps'     => $laps,
            'series'   => $seriesList,
            'lapTimes' => [
                'fastest' => $fastest,
                'slowest' => $slowest,
            ],
        ];
    }

    /**
     * @param array<int, array{lap: int, pit: boolean, time: int}> $lapsList
     *
     * @return array<int, int>
     */
    private function computeLapTimes(array $lapsList, string $max): array
    {
        $lapTimes = [];
        for ($key = 0, $length = count($lapsList); $key < $length; ++$key) {
            $lap = $lapsList[$key];

            if (true === $lap['pit']) { // If it is an inlap
                unset($lapTimes[$lap['lap'] - 1]);
                ++$key; // Skip two because we want to skip the outlap also

                continue;
            }

            $lapTime = (int) $lap['time'];
            if ($lapTime > $max) {
                continue;
            }

            $lapTimes[$lap['lap']] = $lapTime;
        }

        return $lapTimes;
    }
}
