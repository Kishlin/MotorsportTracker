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

        $championship = Championship::fromResponse($result);

        foreach ($this->mutators as $mutator) {
            $mutator->apply($championship);
        }

        return $championship;
    }
}
