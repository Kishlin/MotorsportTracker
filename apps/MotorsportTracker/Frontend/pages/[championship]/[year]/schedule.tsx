// @ts-ignore
import React from 'react';

import Layout from '../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import scheduleApi from '../../../src/MotorsportTracker/Schedule/Api/ScheduleApi';
import championships from '../../../src/MotorsportTracker/Config/Championships';
import { EventsSchedule } from '../../../src/MotorsportTracker/Shared/Types';
import ScheduleEventsList from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import ChampionshipContainer from '../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ChampionshipNavbar from '../../../src/MotorsportTracker/Championship/Nav/ChampionshipNavbar';

declare type ChampionshipSchedulePathParams = {
    params: {
        championship: string,
        year: string,
    },
};

declare type ChampionshipSchedulePageProps = {
    events: EventsSchedule,
    championship: string,
    year: string,
    page: string,
    firstDay: number,
    lastDay: number,
}

const ChampionshipSchedulePage: React.FunctionComponent<ChampionshipSchedulePageProps> = ({
    events,
    firstDay,
    lastDay,
    championship,
    year,
    page,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ChampionshipContainer>
                <ChampionshipNavbar championship={championship} year={year} page={page} />
                <ScheduleEventsList firstDay={firstDay} lastDay={lastDay} events={events} />
            </ChampionshipContainer>
        )}
    />
);

export const getStaticProps = async ({ params: { championship, year } }: ChampionshipSchedulePathParams) => {
    const events = await scheduleApi(championships[championship].name, year);

    const yearAsInt = parseInt(year, 10);

    const firstDay = (new Date(yearAsInt, 0, 1)).getTime();
    const lastDay = (new Date(yearAsInt, 11, 31)).getTime();

    const props = {
        events,
        firstDay,
        lastDay,
        championship,
        year,
        page: 'schedule',
    };

    return { props };
};

export async function getStaticPaths(): Promise<{ paths: Array<ChampionshipSchedulePathParams>, fallback: boolean }> {
    const paths: Array<ChampionshipSchedulePathParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.forEach((year: number) => {
            paths.push({ params: { championship: slug, year: year.toString() } });
        });
    });

    return { paths, fallback: false };
}

export default ChampionshipSchedulePage;
