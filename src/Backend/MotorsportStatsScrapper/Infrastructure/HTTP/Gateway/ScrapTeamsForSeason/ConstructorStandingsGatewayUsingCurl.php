<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Gateway\ScrapTeamsForSeason;

use JsonException;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapTeamsForSeason\ConstructorStandingsGateway;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapTeamsForSeason\ConstructorStandingsResponse;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\Client;
use Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\HTTP\Client\MotorsportStatsAPIClient;
use RuntimeException;

final class ConstructorStandingsGatewayUsingCurl implements ConstructorStandingsGateway
{
    use MotorsportStatsAPIClient;

//    private const FORMULA_ONE_1958 = '13058812-75f8-4910-8348-dc089c76146c';
//    private const FORMULA_ONE_1959 = '02888d0f-6680-43ba-afd1-0b2afa989422';
//    private const FORMULA_ONE_1960 = 'b3f077ab-3fae-417d-9279-c9bf36baece3';
//    private const FORMULA_ONE_1961 = 'ce59bbe6-c2de-4c77-bb75-38b3d9bac1f7';
//    private const FORMULA_ONE_1962 = '9f698b55-5589-4ad5-81f1-f47d78a3b71c';
//    private const FORMULA_ONE_1963 = '5b5e2e28-edb5-4c78-a87f-1e742d7293a7';
//    private const FORMULA_ONE_1964 = '33934282-3a78-4e14-9413-d96f4af2106c';
//    private const FORMULA_ONE_1965 = '4a5edf36-8b5c-48ee-880d-f817e4b28224';
//    private const FORMULA_ONE_1966 = '02d47334-20e4-4445-8968-d390f4f159ce';
//    private const FORMULA_ONE_1967 = '8b36ff57-f0f4-420c-998d-934bd6afc34d';
//    private const FORMULA_ONE_1968 = 'b597bc46-6352-45f9-add5-25f7188a0dd8';
//    private const FORMULA_ONE_1969 = 'e2d21da6-a8be-4186-8c73-483c53254c89';
//    private const FORMULA_ONE_1970 = '86b03df7-4864-4c6a-8d44-a004957c3cdd';
//    private const FORMULA_ONE_1971 = 'd2fffdfb-15ae-42ad-9b44-19083469996f';
//    private const FORMULA_ONE_1972 = '41949676-c92d-49b6-b163-8f93994ef8de';
//    private const FORMULA_ONE_1973 = 'cab797de-e99d-45ef-a088-2359e0e78811';
//    private const FORMULA_ONE_1974 = 'e3e4becc-2056-4389-a60b-7ec2bc04b276';
//    private const FORMULA_ONE_1975 = 'd1174337-a005-45fb-a908-f2cb1bead0b7';
//    private const FORMULA_ONE_1976 = 'd270f23d-6fcd-4195-9be9-ce706292717a';
//    private const FORMULA_ONE_1977 = '18b957d9-8d9a-4ec1-ac2a-22a0c27919b2';
//    private const FORMULA_ONE_1978 = '9c833d1b-251d-45da-8d8d-3fac36e4402e';
    private const FORMULA_ONE_1979 = '710da00c-7f89-48e1-9e20-4b2eafe2dc18';
    private const FORMULA_ONE_1980 = '69077045-426d-4a17-9de0-42a8a419b2d2';
    private const FORMULA_ONE_1981 = '7c28df77-3649-4e0e-a2c5-e3ce9a23bf03';
    private const FORMULA_ONE_1983 = 'b7b4b8b3-46d6-4e04-9072-b6f76fe46433';
    private const FORMULA_ONE_1987 = '351854d2-46ac-4c9e-8543-73599930485f';
    private const FORMULA_ONE_2006 = 'e9f9e395-efae-45fa-b5e9-fbec61128db3';
    private const FORMULA_ONE_2007 = 'a70b1a24-718e-45ad-b870-746a272211ef';
    private const FORMULA_ONE_2008 = 'c0b81828-4dc2-40c0-b9ff-ffd62af0f2e3';
    private const FORMULA_ONE_2009 = 'a542ac86-7b09-41f5-9be0-965fd871b535';
    private const FORMULA_ONE_2010 = '4635fd34-9e44-4d54-9d9c-4b870544eabd';
    private const FORMULA_ONE_2011 = '0b5dfcaf-3adc-4bf9-bf22-edd69e778d58';
    private const FORMULA_ONE_2012 = '0689f907-57d9-4f25-b738-c8bf33b61121';

    private const LOTUS_RENAULT      = '67925b5a-b48b-4951-a805-b067a103ee6a';
    private const LOTUS_COSWORTH     = '00cf4d07-2d35-4044-aea2-fe69a037b160';
    private const HRT_COSWORTH       = '46be08ad-2f5d-40a9-8dde-2f192b655803';
    private const BMW_SAUBER_FERRARI = '90159edc-81bb-45b7-81a1-cc8407fdcc11';
    private const BMW_SAUBER         = '20e94fa1-a9be-4470-a311-c469f2947d44';
    private const MARCH_FORD         = '8adf4534-e2a1-4714-ab24-13acde06ed8c';
    private const WILLIAM_FORD       = 'e0c316c6-a17f-4088-99db-b4b105b38cd4';
    private const ENSIGN_FORD        = '63a81ca2-ca55-48df-98c3-faf2dce63c50';
    private const LOTUS_FORD         = '2b45f6e8-fb9d-4536-8761-5d6f825a0ea3';
    private const BRABHAM_FORD       = '9deb9fcf-dbc9-4e59-b8b1-bb95d19213de';
//    private const MCLAREN_FORD       = '9a8f3237-e914-4ecd-82ca-a84dd01bb23b';

    private const url = 'https://api.motorsportstats.com/widgets/1.0.0/seasons/%s/standings/constructors';

    public function __construct(
        private readonly Client $client,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function fetch(string $seasonRef): ConstructorStandingsResponse
    {
        var_dump($seasonRef);

        $url = sprintf(self::url, $seasonRef);

        $response = $this->client->fetch($url, $this->headers());

        /**
         * @var array{
         *     standings: array<array{
         *         constructor: array{name: string, uuid: string, picture: string},
         *         team: null|array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
         *         countryRepresenting: null|array{name: string, uuid: string, picture: string},
         *         position: int,
         *         points: float,
         *         analytics: array{
         *             wins: int,
         *         },
         *     }>,
         * } $data
         */
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        $filteredData = [];

        foreach ($data['standings'] as $standing) {
            $filteredData[] = $this->dataWithCompletionIfNeeded($standing, $seasonRef);
        }

        return ConstructorStandingsResponse::withStandings(['standings' => $filteredData]);
    }

    /**
     * @param array{
     *     constructor: array{name: string, uuid: string, picture: string},
     *     team: null|array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     countryRepresenting: null|array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         wins: int,
     *     },
     * } $standing
     *
     * @return array{
     *     constructor: array{name: string, uuid: string, picture: string},
     *     team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
     *     countryRepresenting: array{name: string, uuid: string, picture: string},
     *     position: int,
     *     points: float,
     *     analytics: array{
     *         wins: int,
     *     },
     * }
     */
    private function dataWithCompletionIfNeeded(array $standing, string $seasonRef): array
    {
        if (null !== $standing['team'] && null !== $standing['countryRepresenting']) {
            return $standing;
        }

        if (in_array($seasonRef, [self::FORMULA_ONE_2010, self::FORMULA_ONE_2011, self::FORMULA_ONE_2012], true)) {
            if (self::HRT_COSWORTH === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->HRTF1Team(),
                    'countryRepresenting' => $this->spain(),
                ];
            }

            if (self::LOTUS_RENAULT === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->teamLotus(),
                    'countryRepresenting' => $this->malaysia(),
                ];
            }

            if (self::LOTUS_COSWORTH === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->lotusRacing(),
                    'countryRepresenting' => $this->malaysia(),
                ];
            }

            if (self::BMW_SAUBER_FERRARI === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->bmwSauber(),
                    'countryRepresenting' => $this->switzerland(),
                ];
            }
        } elseif (in_array(
            $seasonRef,
            [self::FORMULA_ONE_2009, self::FORMULA_ONE_2008, self::FORMULA_ONE_2007, self::FORMULA_ONE_2006],
            true,
        )) {
            if (self::BMW_SAUBER === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->bmwSauber(),
                    'countryRepresenting' => $this->germany(),
                ];
            }
        } elseif (self::FORMULA_ONE_1987 === $seasonRef) {
            if (self::MARCH_FORD === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->leytonHouse(),
                    'countryRepresenting' => $this->greatBritain(),
                ];
            }
        } elseif (self::FORMULA_ONE_1983 === $seasonRef) {
            if (self::WILLIAM_FORD === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->tagWilliams(),
                    'countryRepresenting' => $this->greatBritain(),
                ];
            }
        } elseif (self::FORMULA_ONE_1981 === $seasonRef) {
            if (self::ENSIGN_FORD === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->ensignRacing(),
                    'countryRepresenting' => $this->greatBritain(),
                ];
            }
        } elseif (self::FORMULA_ONE_1980 === $seasonRef) {
            if (self::WILLIAM_FORD === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->albilabWilliams(),
                    'countryRepresenting' => $this->greatBritain(),
                ];
            }

            if (self::BRABHAM_FORD === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->parmalatRacing(),
                    'countryRepresenting' => $this->greatBritain(),
                ];
            }
        } elseif (self::FORMULA_ONE_1979 === $seasonRef) {
            if (self::LOTUS_FORD === $standing['constructor']['uuid']) {
                return [
                    ...$standing,
                    'team'                => $this->martiniRacing(),
                    'countryRepresenting' => $this->greatBritain(),
                ];
            }
        }

        var_dump($standing);

        throw new RuntimeException('Incomplete data.');
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function HRTF1Team(): array
    {
        return [
            'name'    => 'HRT F1 Team',
            'uuid'    => 'f1396f26-32dd-4c1b-b1ba-07b9d13e7f2f',
            'colour'  => '#B43515',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_B43515.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_B43515.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function teamLotus(): array
    {
        return [
            'name'    => 'Team Lotus',
            'uuid'    => '65b6428b-4e06-482b-b873-2690c30d1d95',
            'colour'  => '#2B4562',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_2B4562.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_2B4562.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function lotusRacing(): array
    {
        return [
            'name'    => 'Lotus Racing',
            'uuid'    => '65b6428b-4e06-482b-b873-2690c30d1d95',
            'colour'  => '#CBD4DB',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_CBD4DB.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_CBD4DB.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function bmwSauber(): array
    {
        return [
            'name'    => 'BMW Sauber F1 Team',
            'uuid'    => 'dfb0d4f6-96a4-4256-85f3-5ccabaf314de',
            'colour'  => '#97186F',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_97186F.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_97186F.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function leytonHouse(): array
    {
        return [
            'name'    => 'Leyton House March Racing Team',
            'uuid'    => '424a65aa-2a02-4290-945e-35a62322cfc1',
            'colour'  => '#E05A17',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_E05A17.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_E05A17.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function tagWilliams(): array
    {
        return [
            'name'    => 'TAG Williams Team',
            'uuid'    => '4d5a3a4d-2dd6-446a-96fd-9055206f58a9',
            'colour'  => '#005AFF',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_005AFF.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_005AFF.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function ensignRacing(): array
    {
        return [
            'name'    => 'Ensign Racing Team',
            'uuid'    => '886275f1-efe8-4fce-ad64-2f8c16950c46',
            'colour'  => '#900000',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_900000.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_900000.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function albilabWilliams(): array
    {
        return [
            'name'    => 'Albilad-Williams Racing Team',
            'uuid'    => '4d5a3a4d-2dd6-446a-96fd-9055206f58a9',
            'colour'  => '#005AFF',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_005AFF.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_005AFF.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function parmalatRacing(): array
    {
        return [
            'name'    => 'Parmalat Racing Team',
            'uuid'    => '274dbe26-5782-4cc5-9330-5964a96d778d',
            'colour'  => '#FDFCFC',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_FDFCFC.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_FDFCFC.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, colour: string, picture: string, carIcon: string}
     */
    private function martiniRacing(): array
    {
        return [
            'name'    => 'Martini Racing Team Lotus',
            'uuid'    => '2f8023cf-138a-43bc-a757-658177433f3e',
            'colour'  => '#5D0E88',
            'picture' => 'https://assets.motorsportstats.com/team/icon/teamColour_5D0E88.svg',
            'carIcon' => 'https://assets.motorsportstats.com/carIcon/Default/caricon_RaceCar_5D0E88.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, picture: string}
     */
    private function spain(): array
    {
        return [
            'name'    => 'Spain',
            'uuid'    => '92334886-6536-401f-9ba2-4677c4fbd0ca',
            'picture' => 'https://assets.motorsportstats.com/flags/svg/es.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, picture: string}
     */
    private function malaysia(): array
    {
        return [
            'name'    => 'Malaysia',
            'uuid'    => 'bdb7e3d0-3896-4dc1-89d5-93cadb0b2d46',
            'picture' => 'https://assets.motorsportstats.com/flags/svg/my.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, picture: string}
     */
    private function switzerland(): array
    {
        return [
            'name'    => 'Switzerland',
            'uuid'    => '3de38cb4-5996-49c5-ad28-31dd5c662998',
            'picture' => 'https://assets.motorsportstats.com/flags/svg/ch.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, picture: string}
     */
    private function germany(): array
    {
        return [
            'name'    => 'Germany',
            'uuid'    => 'c9018443-af50-4f5f-be39-3d9682a6838e',
            'picture' => 'https://assets.motorsportstats.com/flags/svg/de.svg',
        ];
    }

    /**
     * @return array{name: string, uuid: string, picture: string}
     */
    private function greatBritain(): array
    {
        return [
            'name'    => 'Great Britain',
            'uuid'    => '5378198e-1d1e-49ef-bbc9-4e43383b4d3e',
            'picture' => 'https://assets.motorsportstats.com/flags/svg/gb.svg',
        ];
    }
}
