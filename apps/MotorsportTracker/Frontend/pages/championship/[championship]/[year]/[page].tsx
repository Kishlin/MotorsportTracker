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

declare type ChampionshipPathParams = {
    params: {
        championship: string,
        year: string,
        page: 'schedule'|'standings-constructor'|'standings-team'|'standings-driver',
    },
};

interface ChampionshipPageProps {
    availableStandings: AvailableStandings,
}

interface ChampionshipStandingPageProps extends ChampionshipPageProps {
    standings: List<Array<Standing>>,
    type: StandingType,
}

interface ChampionshipSchedulePageProps extends ChampionshipPageProps {
    events: EventsSchedule,
    firstDay: number,
    lastDay: number,
}

declare type Props = ChampionshipStandingPageProps | ChampionshipSchedulePageProps;

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
        };
    }

    if (true === availableStandings.constructor && 'standings-constructor' === page) {
        const constructorStandings = await constructorStandingsApi(championship, year);

        return { props: { availableStandings, standings: constructorStandings.standings, type: 'constructor' } };
    }

    if (true === availableStandings.team && 'standings-team' === page) {
        const teamStandings = await teamStandingsApi(championship, year);

        return { props: { availableStandings, standings: teamStandings.standings, type: 'team' } };
    }

    if (true === availableStandings.driver && 'standings-driver' === page) {
        const driverStandings = await driverStandingsApi(championship, year);

        return { props: { availableStandings, standings: driverStandings.standings, type: 'driver' } };
    }

    return { notFound: true };
};

export async function getStaticPaths(): Promise<{ paths: Array<ChampionshipPathParams>, fallback: boolean }> {
    const paths: Array<ChampionshipPathParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.forEach(async (year: number) => {
            const availableStandings = await availableStandingsApi(slug, year.toString());

            ['constructor', 'team', 'driver'].forEach((type: StandingType) => {
                if (true === availableStandings[type]) {
                    paths.push({ params: { championship: slug, year: year.toString(), page: `standings-${type}` } });
                }
            });

            paths.push({ params: { championship: slug, year: year.toString(), page: 'schedule' } });
        });
    });

    return { paths, fallback: false };
}

export default ChampionshipSchedulePage;