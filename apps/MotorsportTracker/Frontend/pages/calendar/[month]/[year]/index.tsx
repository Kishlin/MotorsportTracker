// @ts-ignore
import React from 'react';

import Calendar from '../../../../src/Calendar/Component/Calendar';
import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import listOfAllMonths from '../../../../src/Calendar/Utils/Date/listOfAllMonths';

declare type CalendarPathParams = {
    params: {
        month: string,
        year: string,
    },
};

declare type CalendarProps = {
    month: string,
    year: string,
    events: {},
}

const CalendarPage: React.FunctionComponent<CalendarProps> = ({ events, year, month }) => {
    const date = new Date(Date.parse(`${month} 1, ${year}`));

    console.log(events);

    return (
        <Layout>
            <Calendar date={date} />
        </Layout>
    );
};

export const getStaticProps = async ({ params: { month, year } }: CalendarPathParams) => {
    const props: CalendarProps = { year, month, events: {} };

    const response = await fetch(`http://backend:8000/api/v1/events/calendar/${month}/${year}`);
    props.events = await response.json();

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
