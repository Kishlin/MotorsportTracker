<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity;

final class Championship
{
    /**
     * @param Event[] $events
     */
    private function __construct(
        private readonly Series $series,
        private readonly array $events,
    ) {
    }

    public function series(): Series
    {
        return $this->series;
    }

    /**
     * @return Event[]
     */
    public function events(): array
    {
        return $this->events;
    }

    /**
     * @param array{
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
     *                  status: null|string,
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
     *                      status: null|string,
     *                      hasResults: bool,
     *                      startTimeUtc: null|int,
     *                      endTimeUtc: null|int,
     *                  }[],
     *              }[],
     *          }
     *      }
     *  } $content
     */
    public static function fromResponse(array $content): self
    {
        return new self(
            Series::fromData($content['pageProps']['lastChampions']['series']),
            array_map(
                static function ($data) { return Event::fromData($data); },
                $content['pageProps']['calendar']['events'],
            ),
        );
    }
}
