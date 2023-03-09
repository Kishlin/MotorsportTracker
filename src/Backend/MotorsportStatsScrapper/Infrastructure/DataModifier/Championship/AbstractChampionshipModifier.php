<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\DataModifier\Championship;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity\Championship;

abstract class AbstractChampionshipModifier
{
    public function apply(Championship $championship): void
    {
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
     *              season: array{
     *                  slug: string,
     *              },
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
     *  } $data
     *
     * @return array{
     *      pageProps: array{
     *          lastChampions: array{
     *              series: array{
     *                  name: string,
     *                  slug: string,
     *                  name: string,
     *              },
     *          },
     *          calendar: array{
     *              season: array{
     *                  slug: string,
     *              },
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
     *  }
     */
    public function preFilter(array $data): array
    {
        return $data;
    }
}
