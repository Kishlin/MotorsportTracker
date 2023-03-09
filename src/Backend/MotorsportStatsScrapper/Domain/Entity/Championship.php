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
        private array $events,
    ) {
    }

    public function removeEventsCancelledOrPostponed(): void
    {
        $filteredEvents = array_filter(
            $this->events,
            static function (Event $event) {
                return 'Cancelled' !== $event->status() && 'Postponed' !== $event->status();
            },
        );

        $this->events = $filteredEvents;
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
        $content = json_decode($response, true);

        return new self(
            Series::fromData($content['pageProps']['lastChampions']['series']),
            array_map(
                static function ($data) { return Event::fromData($data); },
                $content['pageProps']['calendar']['events'],
            ),
        );
    }
}
