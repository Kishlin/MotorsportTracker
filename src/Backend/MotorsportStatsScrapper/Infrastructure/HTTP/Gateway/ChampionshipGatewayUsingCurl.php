<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway;

use Kishlin\Backend\MotorsportStatsScrapper\Application\SyncChampionship\ChampionshipGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Mutator\Championship\ChampionshipMutator;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class ChampionshipGatewayUsingCurl implements ChampionshipGateway
{
    private const URL_TEMPLATE = 'https://motorsportstats.com/_next/data/lsVIRmXxl3G1s77SX0kjm/series/%s/calendar/%d.json?slug=formula-one&season=%d';

    /** @var ChampionshipMutator[] */
    private iterable $mutators;

    /**
     * @param ChampionshipMutator[] $mutators
     */
    public function __construct(
        #[TaggedIterator('kishlin.motorsportcache.infrastructure.mutator.championship')] iterable $mutators,
        private readonly Client $client,
    ) {
        $this->mutators = $mutators;
    }

    public function fetch(string $seriesSlug, int $year): Championship
    {
        $url = sprintf(self::URL_TEMPLATE, $seriesSlug, $year, $year);

        var_dump($url);

        $result = $this->client->fetch($url);

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
         */
        $data = json_decode($result, true);

        foreach ($this->mutators as $mutator) {
            $data = $mutator->preFilter($data);
        }

        $championship = Championship::fromResponse($data);

        foreach ($this->mutators as $mutator) {
            $mutator->apply($championship);
        }

        return $championship;
    }
}
