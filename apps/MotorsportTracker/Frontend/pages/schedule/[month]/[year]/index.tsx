// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import listOfAllMonths from '../../../../src/MotorsportTracker/Schedule/Utils/Date/listOfAllMonths';
import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import ScheduleNavigation from '../../../../src/MotorsportTracker/Schedule/Ui/ScheduleNavigation';
import ScheduleEventsList from '../../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import ScheduleContainer from '../../../../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import ScheduleTitle from '../../../../src/MotorsportTracker/Schedule/Ui/ScheduleTitle';
import calendarApi from '../../../../src/MotorsportTracker/Schedule/Api/CalendarApi';
import { EventsSchedule } from '../../../../src/MotorsportTracker/Shared/Types';

declare type SchedulePathParams = {
    params: {
        month: string,
        year: string,
    },
};

declare type MonthlySchedulePageProps = {
    events: EventsSchedule,
    date: number,
}

const MonthlySchedulePage: React.FunctionComponent<MonthlySchedulePageProps> = ({
    events,
    date,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ScheduleContainer>
                <ScheduleTitle />
                <ScheduleNavigation date={new Date(date)} />
                <ScheduleEventsList events={events} />
            </ScheduleContainer>
        )}
    />
);

export const getStaticProps = async ({ params: { month, year } }: SchedulePathParams) => {
    const events = await calendarApi(month, year);

    return { props: { events, date: new Date(Date.parse(`${month} 1, ${year}`)).getTime() } };
};

export async function getStaticPaths(): Promise<{ paths: Array<SchedulePathParams>, fallback: boolean }> {
    const paths: Array<SchedulePathParams> = [];

    for (let year = 1950; 2024 >= year; year += 1) {
        paths.push(...listOfAllMonths().map((month: string) => ({ params: { year: year.toString(), month } })));
    }

    return { paths, fallback: false };
}

export default MonthlySchedulePage;
