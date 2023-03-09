<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\DataModifier\Championship;

final class SessionTypePatcher extends AbstractChampionshipModifier
{
    /**
     * {@inheritDoc}
     */
    public function preFilter(array $data): array
    {
        foreach ($data['pageProps']['calendar']['events'] as $key => $event) {
            $mappedSessions = [];
            foreach ($event['sessions'] as $session) {
                $name = $session['session']['name'];

                if (str_starts_with($name, 'Warm Up') || str_starts_with($name, 'Pre-')) {
                    continue;
                }

                $mappedName = $this->mappedSessionName($name);

                $mappedSessions[] = [
                    ...$session,
                    'session' => [
                        'name'      => $mappedName,
                        'shortName' => null,
                        'code'      => null,
                        'slug'      => $session['session']['slug'],
                    ],
                ];
            }

            $data['pageProps']['calendar']['events'][$key]['sessions'] = $mappedSessions;
        }

        return $data;
    }

    private function mappedSessionName(string $source): string
    {
        if ('practice' === lcfirst($source)) {
            return 'Free Practice';
        }

        if (str_starts_with(lcfirst($source), 'practice ')) {
            return 'Free Practice ' . substr($source, 9);
        }

        if ('free practice' === strtolower(substr($source, 4))) {
            return 'Free Practice ' . substr($source, 0, 1);
        }

        if (str_ends_with(strtolower($source), ' practice') && is_numeric(substr($source, 0, 1))) {
            return 'Free Practice ' . substr($source, 0, 1);
        }

        return ucwords($source);
    }
}
