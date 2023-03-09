// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import firstSundayAfterEndOfMonthDate from '../../../../src/MotorsportTracker/Schedule/Utils/Date/firstSundayAfterEndOfMonthDate';
import firstMondayBeforeOrAtDate from '../../../../src/MotorsportTracker/Schedule/Utils/Date/firstMondayBeforeOrAtDate';
import listOfAllMonths from '../../../../src/MotorsportTracker/Schedule/Utils/Date/listOfAllMonths';
import { EventsSchedule } from '../../../../src/MotorsportTracker/Shared/Types';
import Schedule from '../../../../src/MotorsportTracker/Schedule/Ui/Schedule';
import calendarApi from '../../../../src/MotorsportTracker/Schedule/Api/CalendarApi';
import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';

declare type SchedulePathParams = {
    params: {
        month: string,
        year: string,
    },
};

declare type MonthlySchedulePageProps = {
    events: EventsSchedule,
    firstDay: number,
    lastDay: number,
    date: number,
}

const MonthlySchedulePage: React.FunctionComponent<MonthlySchedulePageProps> = ({
    events,
    firstDay,
    lastDay,
    date,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={
            <Schedule firstDay={new Date(firstDay)} lastDay={new Date(lastDay)} date={new Date(date)} events={events} />
        }
    />
);

export const getStaticProps = async ({ params: { month, year } }: SchedulePathParams) => {
    const date = new Date(Date.parse(`${month} 1, ${year}`));
    const lastDay = firstSundayAfterEndOfMonthDate(date);
    const firstDay = firstMondayBeforeOrAtDate(date);

    const props: MonthlySchedulePageProps = {
        firstDay: firstDay.getTime(),
        lastDay: lastDay.getTime(),
        date: date.getTime(),
        events: {},
    };

    props.events = await calendarApi(firstDay, lastDay);

    return { props };
};

export async function getStaticPaths(): Promise<{ paths: Array<SchedulePathParams>, fallback: boolean }> {
    const paths: Array<SchedulePathParams> = [];

    for (let year = 1950; 2023 >= year; year += 1) {
        paths.push(...listOfAllMonths().map((month: string) => ({ params: { year: year.toString(), month } })));
    }

    return { paths, fallback: false };
}

export default MonthlySchedulePage;
