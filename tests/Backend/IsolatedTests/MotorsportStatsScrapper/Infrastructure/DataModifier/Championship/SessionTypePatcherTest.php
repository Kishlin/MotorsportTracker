<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportStatsScrapper\Infrastructure\DataModifier\Championship;

use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\DataModifier\Championship\SessionTypePatcher;
use PHPUnit\Framework\TestCase;

final class SessionTypePatcherTest extends TestCase
{
    public function testItChangesWordsToUppercase(): void
    {
        $patcher = new SessionTypePatcher();

        self::assertItChangedTheName('Free Practice', $patcher->preFilter($this->buildWithSession('free practice')));
        self::assertItChangedTheName('Free Practice 1', $patcher->preFilter($this->buildWithSession('free practice 1')));
        self::assertItChangedTheName('Combined Qualifying', $patcher->preFilter($this->buildWithSession('combined qualifying')));
    }

    public function testItFormatsFreePractices(): void
    {
        $patcher = new SessionTypePatcher();

        self::assertItChangedTheName('Free Practice 1', $patcher->preFilter($this->buildWithSession('1st Free Practice')));
        self::assertItChangedTheName('Free Practice 6', $patcher->preFilter($this->buildWithSession('6th Free practice')));
        self::assertItChangedTheName('Free Practice 3', $patcher->preFilter($this->buildWithSession('3rd Practice')));
    }

    public function testItAddsMissingPrefix(): void
    {
        $patcher = new SessionTypePatcher();

        self::assertItChangedTheName('Free Practice', $patcher->preFilter($this->buildWithSession('Practice')));
        self::assertItChangedTheName('Free Practice 3', $patcher->preFilter($this->buildWithSession('Practice 3')));
    }

    public function testItSkipsWarmUps(): void
    {
        $patcher = new SessionTypePatcher();

        self::assertItFilteredTheSessionOut(
            $patcher->preFilter($this->buildWithSession('Warm Up')),
        );

        self::assertItFilteredTheSessionOut(
            $patcher->preFilter($this->buildWithSession('Warm Up 2')),
        );
    }

    public function testItSkipsPreQualifying(): void
    {
        $patcher = new SessionTypePatcher();

        self::assertItFilteredTheSessionOut(
            $patcher->preFilter($this->buildWithSession('Pre-Qualifying')),
        );

        self::assertItFilteredTheSessionOut(
            $patcher->preFilter($this->buildWithSession('Pre-qualifying')),
        );
    }

    /**
     * @param array{
     *      pageProps: array{
     *          calendar: array{
     *              events: array{
     *                  sessions: array{
     *                      session: array{
     *                          name: string,
     *                      },
     *                  }[],
     *              }[],
     *          }
     *      }
     *  } $data
     */
    private static function assertItFilteredTheSessionOut(array $data): void
    {
        self::assertCount(1, $data['pageProps']['calendar']['events']);
        self::assertEmpty($data['pageProps']['calendar']['events'][0]['sessions']);
    }

    /**
     * @param array{
     *      pageProps: array{
     *          calendar: array{
     *              events: array{
     *                  sessions: array{
     *                      session: array{
     *                          name: string,
     *                      },
     *                  }[],
     *              }[],
     *          }
     *      }
     *  } $data
     */
    private static function assertItChangedTheName(string $expected, array $data): void
    {
        self::assertCount(1, $data['pageProps']['calendar']['events']);
        self::assertCount(1, $data['pageProps']['calendar']['events'][0]['sessions']);
        self::assertSame($expected, $data['pageProps']['calendar']['events'][0]['sessions'][0]['session']['name']);
    }

    /**
     * @return array{
     *      pageProps: array{
     *          lastChampions: array{
     *              series: array{
     *                  name: string,
     *                  slug: string,
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
    private function buildWithSession(string $name): array
    {
        return [
            'pageProps' => [
                'lastChampions' => [
                    'series' => [
                        'name' => 'series',
                        'slug' => 'series',
                    ],
                ],
                'calendar' => [
                    'season' => [
                        'slug' => 'season',
                    ],
                    'events' => [
                        [
                            'slug'         => 'event',
                            'name'         => 'event',
                            'shortName'    => 'event',
                            'status'       => null,
                            'startTimeUtc' => 0,
                            'endTimeUtc'   => 0,
                            'venue'        => [
                                'name' => 'venue',
                                'slug' => 'venue',
                            ],
                            'country' => [
                                'name'    => 'country',
                                'slug'    => 'country',
                                'picture' => 'country',
                            ],
                            'sessions' => [
                                [
                                    'session' => [
                                        'name'      => $name,
                                        'shortName' => null,
                                        'slug'      => 'session',
                                        'code'      => null,
                                    ],
                                    'status'       => null,
                                    'hasResults'    => false,
                                    'startTimeUtc' => null,
                                    'endTimeUtc'   => null,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
