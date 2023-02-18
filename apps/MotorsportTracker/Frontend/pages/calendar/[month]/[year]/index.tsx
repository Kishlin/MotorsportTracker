// @ts-ignore
import React from 'react';

import Calendar from '../../../../src/Calendar/Component/Calendar';
import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import listOfAllMonths from '../../../../src/Calendar/Utils/Date/listOfAllMonths';
import calendarApi from '../../../../src/Calendar/Api/CalendarApi';
import { EventCalendarMonth } from '../../../../src/Calendar/Types';
import firstSundayAfterEndOfMonthDate from '../../../../src/Calendar/Utils/Date/firstSundayAfterEndOfMonthDate';
import firstMondayBeforeOrAtDate from '../../../../src/Calendar/Utils/Date/firstMondayBeforeOrAtDate';

declare type CalendarPathParams = {
    params: {
        month: string,
        year: string,
    },
};

declare type CalendarProps = {
    events: EventCalendarMonth,
    firstDay: number,
    lastDay: number,
    date: number,
}

const CalendarPage: React.FunctionComponent<CalendarProps> = ({
    events,
    firstDay,
    lastDay,
    date,
}) => (
    <Layout>
        <Calendar firstDay={new Date(firstDay)} lastDay={new Date(lastDay)} date={new Date(date)} events={events} />
    </Layout>
);

export const getStaticProps = async ({ params: { month, year } }: CalendarPathParams) => {
    const date = new Date(Date.parse(`${month} 1, ${year}`));
    const lastDay = firstSundayAfterEndOfMonthDate(date);
    const firstDay = firstMondayBeforeOrAtDate(date);

    const props: CalendarProps = {
        firstDay: firstDay.getTime(),
        lastDay: lastDay.getTime(),
        date: date.getTime(),
        events: {},
    };

    props.events = await calendarApi(firstDay, lastDay);

    return { props };
};

export async function getStaticPaths(): Promise<{ paths: Array<CalendarPathParams>, fallback: boolean }> {
    const paths: Array<CalendarPathParams> = [];

    for (let year = 2022; 2023 >= year; year += 1) {
        paths.push(...listOfAllMonths().map((month: string) => ({ params: { year: year.toString(), month } })));
    }

    return { paths, fallback: false };
}

export default CalendarPage;
