<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph;

use JsonException;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeGraphCommandHandler;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\Event\EmptyTyreHistoryDataEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\Event\MismatchingTyreAndPitHistoriesEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeTyreHistoryGraph\Event\TyreHistoryWithNullValuesEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Application\GraphDataSaverUsingEntity;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\EventGraph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\RaceAndSprintSessionsGateway;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\EventGraphDataValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class ComputeTyreHistoryGraphCommandHandler extends ComputeGraphCommandHandler
{
    private ComputeTyreHistoryGraphCommand $command;

    public function __construct(
        private readonly GraphDataSaverUsingEntity $graphDataSaverUsingEntity,
        private readonly RaceAndSprintSessionsGateway $eventRaceSessionsGateway,
        private readonly TyreHistoryDataGateway $tyreHistoryDataGateway,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UuidGenerator $uuidGenerator,
    ) {
        parent::__construct(
            $this->eventRaceSessionsGateway,
            $this->graphDataSaverUsingEntity,
            $this->eventDispatcher,
        );
    }

    public function __invoke(ComputeTyreHistoryGraphCommand $command): void
    {
        $this->command = $command;

        parent::doInvoke($command);
    }

    /**
     * @throws JsonException
     */
    protected function computeDataForSession(array $session): array
    {
        $tyreHistoryData = $this->tyreHistoryDataGateway->findForSession($session['session']);

        if (empty($tyreHistoryData->data())) {
            $this->eventDispatcher->dispatch(EmptyTyreHistoryDataEvent::forSession($session['session']));

            return [];
        }

        return $this->buildGraphDataForSession($session, $tyreHistoryData);
    }

    protected function createGraph(array $dataPerSession): Graph
    {
        return EventGraph::tyreHistory(
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
    private function buildGraphDataForSession(array $session, TyreHistoryData $tyreHistoryData): array
    {
        $seriesList = [];
        $laps       = 0;

        foreach ($tyreHistoryData->data() as $series) {
            $laps = max($laps, $series['laps']);

            $tyreHistory = [];
            if (null !== $series['tyre_details'] && null !== $series['pit_history']) {
                /** @var array<int, array{laps: int, type: string, wear: string}> $tyreDetails */
                $tyreDetails = json_decode($series['tyre_details'], true, 512, JSON_THROW_ON_ERROR);
                assert(is_array($tyreDetails));

                /** @var array<int, int> $pitHistory */
                $pitHistory = json_decode($series['pit_history'], true, 512, JSON_THROW_ON_ERROR);
                assert(is_array($pitHistory));

                $tyreHistory = [
                    '0' => array_shift($tyreDetails),
                ];

                foreach ($pitHistory as $pitLap) {
                    $tyreDetail = array_shift($tyreDetails);

                    if (null === $tyreDetail) {
                        $this->eventDispatcher->dispatch(
                            MismatchingTyreAndPitHistoriesEvent::forSeries($session['session'], $series, true),
                        );

                        continue 2;
                    }

                    $tyreHistory[$pitLap] = $tyreDetail;
                }

                if (false === empty($tyreDetails)) {
                    $this->eventDispatcher->dispatch(
                        MismatchingTyreAndPitHistoriesEvent::forSeries($session['session'], $series),
                    );
                }

                if (1 === count($tyreDetails)) {
                    $tyreHistory[$laps - $tyreDetails[0]['laps']] = array_shift($tyreDetails);
                }
            } elseif (null !== $series['tyre_details'] && null === $series['pit_history']) {
                /** @var array<int, array{laps: int, type: string, wear: string}> $tyreDetails */
                $tyreDetails = json_decode($series['tyre_details'], true, 512, JSON_THROW_ON_ERROR);
                assert(is_array($tyreDetails));

                $tyreHistory = ['0' => array_pop($tyreDetails)];
            } else {
                $this->eventDispatcher->dispatch(
                    TyreHistoryWithNullValuesEvent::forSeries($session['session'], $series),
                );

                continue;
            }

            $seriesList[] = [
                'color'        => $series['color'],
                'index'        => $this->nextIndexForColor($series['color']),
                'car_number'   => $series['car_number'],
                'short_code'   => $series['short_code'],
                'laps'         => $series['laps'],
                'tyre_history' => $tyreHistory,
            ];
        }

        if (empty($seriesList)) {
            return [];
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
}
