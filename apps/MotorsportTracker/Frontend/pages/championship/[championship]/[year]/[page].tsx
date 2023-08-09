// @ts-ignore
import React from 'react';
import { useRouter } from 'next/router';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import championships from '../../../../src/MotorsportTracker/Config/Championships';
import ScheduleEventsList from '../../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import ChampionshipContainer from '../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ChampionshipNavbar from '../../../../src/MotorsportTracker/Championship/Nav/ChampionshipNavbar';
import scheduleApi from '../../../../src/MotorsportTracker/Schedule/Api/ScheduleApi';
import availableStandingsApi from '../../../../src/MotorsportTracker/Standing/Api/AvailableStandingsApi';
import constructorStandingsApi from '../../../../src/MotorsportTracker/Standing/Api/ConstructorStandingsApi';
import teamStandingsApi from '../../../../src/MotorsportTracker/Standing/Api/TeamStandingsApi';
import driverStandingsApi from '../../../../src/MotorsportTracker/Standing/Api/DriverStandingsApi';
import { List } from '../../../../src/Shared/Types/Index';
import {
    AvailableStandings,
    EventsSchedule,
    StandingType,
    Standing,
} from '../../../../src/MotorsportTracker/Shared/Types';
import StandingsContent from '../../../../src/MotorsportTracker/Standing/Ui/StandingsContent';
import driversAnalyticsApi from '../../../../src/MotorsportTracker/Analytics/Api/DriversAnalyticsApi';
import constructorsAnalyticsApi from '../../../../src/MotorsportTracker/Analytics/Api/ConstructorsAnalyticsApi';
import teamsAnalyticsApi from '../../../../src/MotorsportTracker/Analytics/Api/TeamsAnalyticsApi';
import {
    ConstructorAnalytics,
    DriverAnalytics,
    TeamAnalytics,
} from '../../../../src/MotorsportTracker/Analytics/Types/Index';
import AnalyticsContent from '../../../../src/MotorsportTracker/Analytics/Ui/AnalyticsContent';

declare type ChampionshipPathParams = {
    params: {
        championship: string,
        year: string,
        page: 'schedule'|'standings-constructor'|'standings-team'|'standings-driver'|'stats',
    },
};

interface ChampionshipPageProps {
    availableStandings: AvailableStandings,
}

interface ChampionshipStandingPageProps extends ChampionshipPageProps {
    standings: List<Array<Standing>>,
    type: StandingType,
}

interface ChampionshipStatsPageProps extends ChampionshipPageProps {
    constructorsAnalytics: Array<ConstructorAnalytics>,
    driversAnalytics: Array<DriverAnalytics>,
    teamsAnalytics: Array<TeamAnalytics>,
}

interface ChampionshipSchedulePageProps extends ChampionshipPageProps {
    events: EventsSchedule,
    firstDay: number,
    lastDay: number,
}

declare type Props = ChampionshipStandingPageProps | ChampionshipSchedulePageProps | ChampionshipStatsPageProps;

const ChampionshipSchedulePage: React.FunctionComponent<ChampionshipPageProps> = (props: Props) => {
    const router = useRouter();
    const { championship, year, page } = router.query;

    if (undefined === championship) {
        return <noscript />;
    }

    const { availableStandings } = props;

    if ('schedule' === page) {
        const { events, firstDay, lastDay } = props as ChampionshipSchedulePageProps;

        return (
            <Layout
                menu={<MotorsportTrackerMenu />}
                content={(
                    <ChampionshipContainer>
                        <ScheduleEventsList firstDay={firstDay} lastDay={lastDay} events={events} />
                    </ChampionshipContainer>
                )}
                subHeader={(
                    <ChampionshipNavbar
                        availableStandings={availableStandings}
                        championship={championship}
                        year={year}
                        page={page}
                    />
                )}
            />
        );
    }

    if ('stats' === page) {
        const { constructorsAnalytics, driversAnalytics, teamsAnalytics } = props as ChampionshipStatsPageProps;

        return (
            <Layout
                menu={<MotorsportTrackerMenu />}
                content={(
                    <ChampionshipContainer>
                        <AnalyticsContent
                            constructorsAnalytics={constructorsAnalytics}
                            driversAnalytics={driversAnalytics}
                            teamsAnalytics={teamsAnalytics}
                        />
                    </ChampionshipContainer>
                )}
                subHeader={(
                    <ChampionshipNavbar
                        availableStandings={availableStandings}
                        championship={championship}
                        year={year}
                        page={page}
                    />
                )}
            />
        );
    }

    const { standings, type } = props as ChampionshipStandingPageProps;

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={(
                <ChampionshipContainer>
                    <StandingsContent standings={standings} type={type} />
                </ChampionshipContainer>
            )}
            subHeader={(
                <ChampionshipNavbar
                    availableStandings={availableStandings}
                    championship={championship}
                    year={year}
                    page={page}
                />
            )}
        />
    );
};

export const getStaticProps = async ({ params: { championship, year, page } }: (ChampionshipPathParams)) => {
    const availableStandings = await availableStandingsApi(championship, year.toString());

    if ('schedule' === page) {
        const events = await scheduleApi(championships[championship].slug, year);

        const yearAsInt = parseInt(year, 10);

        const firstDay = (new Date(yearAsInt, 0, 1)).getTime();
        const lastDay = (new Date(yearAsInt, 11, 31)).getTime();

        return {
            props: {
                availableStandings,
                events,
                firstDay,
                lastDay,
            },
            revalidate: 60,
        };
    }

    if ('stats' === page) {
        const constructorsAnalytics = await constructorsAnalyticsApi(championships[championship].slug, year);
        const driversAnalytics = await driversAnalyticsApi(championships[championship].slug, year);
        const teamsAnalytics = await teamsAnalyticsApi(championships[championship].slug, year);

        return {
            props: {
                availableStandings,
                constructorsAnalytics,
                driversAnalytics,
                teamsAnalytics,
            },
            revalidate: 60,
        };
    }

    if (true === availableStandings.constructor && 'standings-constructor' === page) {
        const constructorStandings = await constructorStandingsApi(championship, year);

        return {
            props: { availableStandings, standings: constructorStandings.standings, type: 'constructor' },
            revalidate: 60,
        };
    }

    if (true === availableStandings.team && 'standings-team' === page) {
        const teamStandings = await teamStandingsApi(championship, year);

        return {
            props: { availableStandings, standings: teamStandings.standings, type: 'team' },
            revalidate: 60,
        };
    }

    if (true === availableStandings.driver && 'standings-driver' === page) {
        const driverStandings = await driverStandingsApi(championship, year);

        return {
            props: { availableStandings, standings: driverStandings.standings, type: 'driver' },
            revalidate: 60,
        };
    }

    return { notFound: true };
};

export async function getStaticPaths(): Promise<{ paths: Array<ChampionshipPathParams>, fallback: boolean|'blocking' }> {
    const paths: Array<ChampionshipPathParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.slice(-2).forEach(async (year: number) => {
            const availableStandings = await availableStandingsApi(slug, year.toString());

            ['constructor', 'team', 'driver'].forEach((type: StandingType) => {
                if (true === availableStandings[type]) {
                    paths.push({ params: { championship: slug, year: year.toString(), page: `standings-${type}` } });
                }
            });

            paths.push({ params: { championship: slug, year: year.toString(), page: 'schedule' } });
            paths.push({ params: { championship: slug, year: year.toString(), page: 'stats' } });
        });
    });

    return { paths, fallback: 'blocking' };
}

export default ChampionshipSchedulePage;
