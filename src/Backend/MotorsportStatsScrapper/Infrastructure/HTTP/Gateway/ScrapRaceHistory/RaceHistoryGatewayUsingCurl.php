<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapRaceHistory;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\RaceHistoryGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\RaceHistoryResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;

final readonly class RaceHistoryGatewayUsingCurl extends MotorsportStatsAPIClient implements RaceHistoryGateway
{
    /**
     * @throws JsonException
     */
    public function fetch(string $sessionRef): RaceHistoryResponse
    {
        $response = $this->fetchFromClient($sessionRef);

        if (empty($response)) {
            return RaceHistoryResponse::withRaceHistory(['entries' => [], 'laps' => []]);
        }

        /**
         * @var array{
         *     entries: array{
         *         uuid: string,
         *         carNumber:string,
         *         driver: array{
         *             name: string,
         *             uuid: string,
         *             shortCode: string,
         *             colour: null|string,
         *             picture: string,
         *         },
         *     }[],
         *     laps: array{
         *         lap: int,
         *         carPosition: array{
         *             entryUuid: string,
         *             position: int,
         *             pit: bool,
         *             time: int,
         *             gap: array{
         *                 timeToLead: ?int,
         *                 lapsToLead: ?int,
         *                 timeToNext: ?int,
         *                 lapsToNext: ?int,
         *             },
         *             tyreDetail: array{
         *                 type: string,
         *                 wear: string,
         *                 laps: int,
         *             }[],
         *         }[],
         *     }[],
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return RaceHistoryResponse::withRaceHistory($data);
    }

    protected function url(int $parametersCount): string
    {
        return 'https://api.motorsportstats.com/widgets/1.0.0/sessions/%s/raceHistory';
    }

    protected function topic(): string
    {
        return 'race_history';
    }
}
