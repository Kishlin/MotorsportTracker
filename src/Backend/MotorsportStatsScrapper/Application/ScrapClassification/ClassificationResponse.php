<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

final readonly class ClassificationResponse
{
    /**
     * @param array{
     *     details: array{
     *         finishPosition: int,
     *         gridPosition: ?int,
     *         carNumber: int,
     *         drivers: array<array{
     *             name: string,
     *             firstName: string,
     *             lastName: string,
     *             shortCode: string,
     *             colour: null|string,
     *             uuid: string,
     *             picture: string,
     *         }>,
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         laps: int,
     *         points: float,
     *         time: float,
     *         classifiedStatus: ?string,
     *         avgLapSpeed: float,
     *         fastestLapTime: ?float,
     *         gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
     *         best: array{lap: ?int, time: ?float, fastest: ?bool, speed: ?float}
     *     }[],
     *     retirements: array<array{
     *         driver: array{
     *             name: string,
     *             firstName: string,
     *             lastName: string,
     *             shortCode: string,
     *             colour: null|string,
     *             uuid: string,
     *             picture: string,
     *         },
     *         carNumber: int,
     *         reason: string,
     *         type: string,
     *         dns: bool,
     *         lap: int,
     *         details: null,
     *     }>
     * } $data
     */
    private function __construct(
        private array $data,
    ) {
    }

    /**
     * @return array{
     *     details: array{
     *         finishPosition: int,
     *         gridPosition: ?int,
     *         carNumber: int,
     *         drivers: array<array{
     *             name: string,
     *             firstName: string,
     *             lastName: string,
     *             shortCode: string,
     *             colour: null|string,
     *             uuid: string,
     *             picture: string,
     *         }>,
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         laps: int,
     *         points: float,
     *         time: float,
     *         classifiedStatus: ?string,
     *         avgLapSpeed: float,
     *         fastestLapTime: ?float,
     *         gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
     *         best: array{lap: ?int, time: ?float, fastest: ?bool, speed: ?float}
     *     }[],
     *     retirements: array<array{
     *         driver: array{
     *             name: string,
     *             firstName: string,
     *             lastName: string,
     *             shortCode: string,
     *             colour: null|string,
     *             uuid: string,
     *             picture: string,
     *         },
     *         carNumber: int,
     *         reason: string,
     *         type: string,
     *         dns: bool,
     *         lap: int,
     *         details: null,
     *     }>
     * }
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array{
     *     details: array{
     *         finishPosition: int,
     *         gridPosition: ?int,
     *         carNumber: int,
     *         drivers: array<array{
     *             name: string,
     *             firstName: string,
     *             lastName: string,
     *             shortCode: string,
     *             colour: null|string,
     *             uuid: string,
     *             picture: string,
     *         }>,
     *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *         nationality: array{name: string, uuid: string, picture: string},
     *         laps: int,
     *         points: float,
     *         time: float,
     *         classifiedStatus: ?string,
     *         avgLapSpeed: float,
     *         fastestLapTime: ?float,
     *         gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
     *         best: array{lap: ?int, time: ?float, fastest: ?bool, speed: ?float}
     *     }[],
     *     retirements: array<array{
     *         driver: array{
     *             name: string,
     *             firstName: string,
     *             lastName: string,
     *             shortCode: string,
     *             colour: null|string,
     *             uuid: string,
     *             picture: string,
     *         },
     *         carNumber: int,
     *         reason: string,
     *         type: string,
     *         dns: bool,
     *         lap: int,
     *         details: null,
     *     }>
     * } $data
     */
    public static function withClassification(array $data): self
    {
        return new self($data);
    }
}
