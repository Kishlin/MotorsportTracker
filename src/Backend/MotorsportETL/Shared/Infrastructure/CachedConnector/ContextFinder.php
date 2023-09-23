<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\Shared\Domain\Tools;
use LogicException;

final class ContextFinder
{
    public function urlToContext(string $url): Context
    {
        if (Tools::endsWith($url, 'series')) {
            return Context::SERIES;
        }

        if (Tools::endsWith($url, 'seasons')) {
            return Context::SEASONS;
        }

        /*
         * At this point, url should be a standing url, so one of
         * https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings
         * https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/{type}
         * https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/{type}?seriesClassUuid=%s
         */

        if (Tools::endsWith($url, 'standings')) {
            return Context::STANDINGS;
        }

        if (Tools::endsWith($url, '?seriesClassUuid=%s')) {
            $url = substr($url, 0, -19); // 19 is the length of '?seriesClassUuid=%s'
        }

        if (Tools::endsWith($url, 'standings/teams')) {
            return Context::STANDINGS_TEAMS;
        }

        if (Tools::endsWith($url, 'standings/drivers')) {
            return Context::STANDINGS_DRIVERS;
        }

        if (Tools::endsWith($url, 'standings/constructors')) {
            return Context::STANDINGS_CONSTRUCTORS;
        }

        throw new LogicException("Unknown context for url: {$url}");
    }
}
