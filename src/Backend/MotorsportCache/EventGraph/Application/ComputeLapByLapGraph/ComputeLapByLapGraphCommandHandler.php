<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

use JsonException;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Event\EmptyLapByLapDataEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Event\NoSessionFoundEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway\EventRaceSessionsGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\Gateway\LapByLapDataGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\DeprecatedLapByLapGraphDeletedEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\FailedToSaveEventGraphEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\EventGraph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphType;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\DeleteDeprecatedEventGraphGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\EventGraphGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\EventGraphDataValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class ComputeLapByLapGraphCommandHandler implements CommandHandler
{
    private const BASE_MAX_TIME_RATIO = 1.07;

    /** @var array<string, bool> */
    private array $hasParsedLabelCache;

    public function __construct(
        private readonly DeleteDeprecatedEventGraphGateway $deleteDeprecatedEventGraphGateway,
        private readonly EventRaceSessionsGateway $eventRaceSessionsGateway,
        private readonly LapByLapDataGateway $lapByLapDataGateway,
        private readonly EventGraphGateway $eventGraphGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(ComputeLapByLapGraphCommand $command): void
    {
        $maxTimeRatio = $command->maxTimeRatio() ?? self::BASE_MAX_TIME_RATIO;

        $this->computeGraphsForEvent($command->eventId(), $maxTimeRatio);
    }

    private function computeGraphsForEvent(string $event, float $maxTimeRatio): void
    {
        $sessions = $this->eventRaceSessionsGateway->findForEvent($event);
        if (empty($sessions->sessions())) {
            $this->eventDispatcher->dispatch(NoSessionFoundEvent::create());

            return;
        }

        $graphsData = [];

        foreach ($sessions->sessions() as $session) {
            $history = $this->lapByLapDataGateway->findForSession($session['session'], $maxTimeRatio);
            if (empty($history->data())) {
                $this->eventDispatcher->dispatch(EmptyLapByLapDataEvent::forSession($session['session']));

                continue;
            }

            $graphsData[$session['session']] = $this->buildGraphDataForSession($session, $history);
        }

        $eventGraph = EventGraph::lapByLap(
            new UuidValueObject($this->uuidGenerator->uuid4()),
            new UuidValueObject($event),
            new EventGraphDataValueObject($graphsData),
        );

        if ($this->deleteDeprecatedEventGraphGateway->deleteForEvent($event, EventGraphType::LAP_BY_LAP_PACE)) {
            $this->eventDispatcher->dispatch(DeprecatedLapByLapGraphDeletedEvent::forEvent($event));
        }

        try {
            $this->eventGraphGateway->save($eventGraph);
        } catch (Throwable $e) {
            $this->eventDispatcher->dispatch(FailedToSaveEventGraphEvent::forThrowable($e));

            return;
        }

        $this->eventDispatcher->dispatch(...$eventGraph->pullDomainEvents());
    }

    /**
     * @param array{session: string, type: string} $session
     *
     * @return array{
     *     session: array{id: string, type: string},
     *     series: array<array{
     *         color: string,
     *         car_number: string,
     *         short_code: string,
     *         dashed: bool,
     *         lapTimes: int[],
     *     }>,
     *     lapTimes: array{
     *         slowest: int,
     *         fastest: int,
     *     },
     *     laps: number,
     * }
     *
     * @throws JsonException
     */
    private function buildGraphDataForSession(array $session, LapByLapData $history): array
    {
        $this->hasParsedLabelCache = [];

        $seriesList = [];

        $slowest = $laps = 0;
        $fastest = PHP_INT_MAX;
        foreach ($history->data() as $series) {
            /** @var array<int, array{lap: int, pit: boolean, time: int}> $lapsList */
            $lapsList = json_decode($series['laps'], true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($lapsList));

            $max = $series['max'];

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

            if (false === empty($lapTimes)) {
                $fastest = min($fastest, min($lapTimes));
                $slowest = max($slowest, max($lapTimes));
            }

            $laps = max($laps, count($lapTimes));

            $seriesList[] = [
                'color'      => $series['color'],
                'car_number' => $series['car_number'],
                'short_code' => $series['short_code'],
                'dashed'     => $this->shouldBeDashed($series['color']),
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

    private function shouldBeDashed(string $key): bool
    {
        if (false === array_key_exists($key, $this->hasParsedLabelCache)) {
            $this->hasParsedLabelCache[$key] = true;

            return false;
        }

        return true;
    }
}
