// @ts-ignore
import React, { useState } from 'react';

import previousMonday from '../../src/MotorsportTracker/Schedule/Utils/Date/previousMonday';
import ScheduleScrollable from '../../src/MotorsportTracker/Schedule/Ui/ScheduleScrollable';
import Layout from '../../src/Shared/Ui/Layout/Layout';
import { EventsSchedule } from '../../src/MotorsportTracker/Shared/Types';
import calendarApi from '../../src/MotorsportTracker/Api/CalendarApi';

declare type SchedulePageProps = {
    events: EventsSchedule,
    firstDayString: number,
    lastDayString: number,
};

const SchedulePage: React.FunctionComponent<SchedulePageProps> = ({ firstDayString, lastDayString, events }) => {
    const [firstDay, setFirstDay] = useState<Date>(new Date(firstDayString));
    const [lastDay, setLastDay] = useState<Date>(new Date(lastDayString));

    return (
        <Layout>
            <ScheduleScrollable events={events} firstDay={firstDay} lastDay={lastDay} />
        </Layout>
    );
};

export async function getServerSideProps(): Promise<{ props: SchedulePageProps }> {
    const now = new Date();
    now.setHours(12, 0, 0, 0);

    const mondayBeforeNow = previousMonday(now);

    const nextSundayTwoWeeksAfterNow = new Date(
        mondayBeforeNow.getFullYear(),
        mondayBeforeNow.getMonth(),
        mondayBeforeNow.getDate() + 20,
    );

    const events = await calendarApi(mondayBeforeNow, nextSundayTwoWeeksAfterNow);

    return {
        props: {
            lastDayString: nextSundayTwoWeeksAfterNow.getTime(),
            firstDayString: mondayBeforeNow.getTime(),
            events,
        },
    };
}

export default SchedulePage;
