<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship;

final class SyncChampionshipHTTPResponse
{
    /**
     * @param EventDTO[] $events
     */
    private function __construct(
        private readonly SeriesDTO $series,
        private readonly array $events,
    ) {
    }

    public function series(): SeriesDTO
    {
        return $this->series;
    }

    /**
     * @return EventDTO[]
     */
    public function events(): array
    {
        return $this->events;
    }

    public static function fromResponse(string $response): self
    {
        /** @var array{
         *      pageProps: array{
         *          lastChampions: array{
         *              series: array{
         *                  name: string,
         *                  slug: string,
         *                  name: string,
         *              },
         *          },
         *          calendar: array{
         *              events: array{
         *                  slug: string,
         *                  name: string,
         *                  shortName: string,
         *                  startTimeUtc: int,
         *                  endTimeUtc: int,
         *                  venue: array{
         *                      name: string,
         *                      slug: string,
         *                  },
         *                  country: array{
         *                      name: string,
         *                      slug: string,
         *                      picture: string,
         *                  },
         *                  sessions: array{
         *                      session: array{
         *                          name: string,
         *                          shortName: null|string,
         *                          slug: string,
         *                          code: null|string,
         *                      },
         *                      hasResults: bool,
         *                      startTimeUtc: null|int,
         *                      endTimeUtc: null|int,
         *                  }[],
         *              }[],
         *          }
         *      }
         *  } $content
         */
        $content = json_decode($response, true);

        return new self(
            SeriesDTO::fromData($content['pageProps']['lastChampions']['series']),
            array_map(
                static function ($data) { return EventDTO::fromData($data); },
                $content['pageProps']['calendar']['events'],
            ),
        );
    }
}
