<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory;

final class RaceHistoryResponse
{
    /**
     * @param array{
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
    private function __construct(
        private readonly array $data,
    ) {
    }

    /**
     * @return array{
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
     * }
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @param array{
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
    public static function withRaceHistory(array $data): self
    {
        return new self($data);
    }
}
