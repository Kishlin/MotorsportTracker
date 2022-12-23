// @ts-ignore
import React from 'react';

import Calendar from '../../../../src/Calendar/Component/Calendar';
import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import listOfAllMonths from '../../../../src/Calendar/Utils/Date/listOfAllMonths';
import calendarApi from '../../../../src/Calendar/Api/CalendarApi';
import { EventCalendarMonth } from '../../../../src/Calendar/Types';

declare type CalendarPathParams = {
    params: {
        month: string,
        year: string,
    },
};

declare type CalendarProps = {
    events: EventCalendarMonth,
    month: string,
    year: string,
}

const CalendarPage: React.FunctionComponent<CalendarProps> = ({ events, year, month }) => {
    const date = new Date(Date.parse(`${month} 1, ${year}`));

    return (
        <Layout>
            <Calendar date={date} events={events} />
        </Layout>
    );
};

export const getStaticProps = async ({ params: { month, year } }: CalendarPathParams) => {
    const props: CalendarProps = { year, month, events: {} };

    props.events = await calendarApi(month, year);

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
