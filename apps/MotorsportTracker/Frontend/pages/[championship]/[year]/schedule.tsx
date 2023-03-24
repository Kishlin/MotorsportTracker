// @ts-ignore
import React from 'react';

import Layout from '../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import scheduleApi from '../../../src/MotorsportTracker/Schedule/Api/ScheduleApi';
import championships from '../../../src/MotorsportTracker/Config/Championships';
import { EventsSchedule } from '../../../src/MotorsportTracker/Shared/Types';
import ScheduleContainer from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import ScheduleEventsList from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';

declare type ChampionshipSchedulePathParams = {
    params: {
        championship: string,
        year: string,
    },
};

declare type ChampionshipSchedulePageProps = {
    events: EventsSchedule,
    firstDay: number,
    lastDay: number,
}

const ChampionshipSchedulePage: React.FunctionComponent<ChampionshipSchedulePageProps> = ({
    events,
    firstDay,
    lastDay,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ScheduleContainer>
                <ScheduleEventsList firstDay={firstDay} lastDay={lastDay} events={events} />
            </ScheduleContainer>
        )}
    />
);

export const getStaticProps = async ({ params: { championship, year } }: ChampionshipSchedulePathParams) => {
    const events = await scheduleApi(championships[championship].name, year);

    const yearAsInt = parseInt(year, 10);

    const firstDay = (new Date(yearAsInt, 0, 1)).getTime();
    const lastDay = (new Date(yearAsInt, 11, 31)).getTime();

    return { props: { events, firstDay, lastDay } };
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
