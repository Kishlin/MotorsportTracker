<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapClassification;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\ClassificationGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\ClassificationResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class ClassificationGatewayUsingCurl implements ClassificationGateway
{
    use MotorsportStatsAPIClient;

    private const url = 'https://api.motorsportstats.com/widgets/1.0.0/sessions/%s/classification';

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetch(string $sessionRef): ClassificationResponse
    {
        $url = sprintf(self::url, $sessionRef);

        $response = $this->client->fetch($url, $this->headers());

        /**
         * @var array{
         *     details: array{
         *         finishPosition: int,
         *         gridPosition: ?int,
         *         carNumber: string,
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
         *         carNumber: string,
         *         reason: string,
         *         type: string,
         *         dns: bool,
         *         lap: int,
         *         details: null,
         *     }>
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return ClassificationResponse::withClassification($data);
    }
}
