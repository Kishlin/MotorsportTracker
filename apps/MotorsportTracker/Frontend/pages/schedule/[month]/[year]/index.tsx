// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import firstSundayAfterEndOfMonthDate from '../../../../src/MotorsportTracker/Schedule/Utils/Date/firstSundayAfterEndOfMonthDate';
import firstMondayBeforeOrAtDate from '../../../../src/MotorsportTracker/Schedule/Utils/Date/firstMondayBeforeOrAtDate';
import listOfAllMonths from '../../../../src/MotorsportTracker/Schedule/Utils/Date/listOfAllMonths';
import { EventsSchedule } from '../../../../src/MotorsportTracker/Shared/Types';
import Schedule from '../../../../src/MotorsportTracker/Schedule/Ui/Schedule';
import calendarApi from '../../../../src/MotorsportTracker/Api/CalendarApi';

declare type SchedulePathParams = {
    params: {
        month: string,
        year: string,
    },
};

declare type ScheduleProps = {
    events: EventsSchedule,
    firstDay: number,
    lastDay: number,
    date: number,
}

const SchedulePage: React.FunctionComponent<ScheduleProps> = ({
    events,
    firstDay,
    lastDay,
    date,
}) => (
    <Layout>
        <Schedule firstDay={new Date(firstDay)} lastDay={new Date(lastDay)} date={new Date(date)} events={events} />
    </Layout>
);

export const getStaticProps = async ({ params: { month, year } }: SchedulePathParams) => {
    const date = new Date(Date.parse(`${month} 1, ${year}`));
    const lastDay = firstSundayAfterEndOfMonthDate(date);
    const firstDay = firstMondayBeforeOrAtDate(date);

    const props: ScheduleProps = {
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

    for (let year = 2022; 2023 >= year; year += 1) {
        paths.push(...listOfAllMonths().map((month: string) => ({ params: { year: year.toString(), month } })));
    }

    return { paths, fallback: false };
}

export default SchedulePage;
