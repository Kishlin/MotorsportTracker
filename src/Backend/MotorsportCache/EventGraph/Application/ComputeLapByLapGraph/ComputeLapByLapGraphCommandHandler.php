<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph;

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
    /** @var array<string, bool> */
    private array $hasParsedLabelCache = [];

    public function __construct(
        private readonly DeleteDeprecatedEventGraphGateway $deleteDeprecatedEventGraphGateway,
        private readonly EventRaceSessionsGateway $eventRaceSessionsGateway,
        private readonly LapByLapDataGateway $lapByLapDataGateway,
        private readonly EventGraphGateway $eventGraphGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(ComputeLapByLapGraphCommand $command): ?UuidValueObject
    {
        $event = $command->eventId();

        $sessions = $this->eventRaceSessionsGateway->findForEvent($event);
        if (empty($sessions->sessions())) {
            $this->eventDispatcher->dispatch(NoSessionFoundEvent::create());

            return null;
        }

        $graphsData = [];

        foreach ($sessions->sessions() as $session) {
            $history = $this->lapByLapDataGateway->findForSession($session['session']);
            if (empty($history->data())) {
                $this->eventDispatcher->dispatch(EmptyLapByLapDataEvent::forSession($session['session']));

                continue;
            }

            $graphsData[$session['session']] = $this->buildGraphDataForSession($session, $history, $command);
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

            return null;
        }

        $this->eventDispatcher->dispatch(...$eventGraph->pullDomainEvents());

        return $eventGraph->id();
    }

    private function shouldBeDashed(string $key): bool
    {
        if (false === array_key_exists($key, $this->hasParsedLabelCache)) {
            $this->hasParsedLabelCache[$key] = true;

            return false;
        }

        return true;
    }

    /**
     * @param array{session: string, type: string} $session
     *
     * @return array{
     *     session: array{id: string, type: string},
     *     series: array<array{
     *         color: string,
     *         label: string,
     *         dashed: bool,
     *         lapTimes: int[],
     *     }>,
     *     lapTimes: array{
     *         slowest: int,
     *         fastest: int,
     *     },
     *     laps: number,
     * }
     */
    private function buildGraphDataForSession(array $session, LapByLapData $history, ComputeLapByLapGraphCommand $command): array
    {
        $seriesList = [];

        $slowest = $laps = 0;
        $fastest = PHP_INT_MAX;
        foreach ($history->data() as $series) {
            $lapTimesAsString = explode(',', substr($series['laptimes'], 1, -1));

            $laps = max($laps, $series['laps']);

            $lapTimes = [];
            foreach ($lapTimesAsString as $key => $lapTimeAsString) {
                $lapTime = (int) $lapTimeAsString;

                if ($lapTime < $command->minimumLapTime() || $lapTime > $command->maximumLapTime()) {
                    continue;
                }

                $fastest = min($fastest, $lapTime);
                $slowest = max($slowest, $lapTime);

                $lapTimes[$key] = $lapTime;
            }

            $seriesList[] = [
                'color'    => $series['color'],
                'label'    => $series['label'],
                'dashed'   => $this->shouldBeDashed($series['color']),
                'lapTimes' => $lapTimes,
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
}