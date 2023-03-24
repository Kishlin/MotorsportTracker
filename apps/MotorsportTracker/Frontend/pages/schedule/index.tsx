// @ts-ignore
import React, { useState } from 'react';

import MotorsportTrackerMenu from '../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import previousMonday from '../../src/MotorsportTracker/Schedule/Utils/Date/previousMonday';
import { EventsSchedule } from '../../src/MotorsportTracker/Shared/Types';
import calendarApi from '../../src/MotorsportTracker/Schedule/Api/CalendarApi';
import Layout from '../../src/Shared/Ui/Layout/Layout';
import ScheduleContainer from '../../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import ScheduleTitleUpcoming from '../../src/MotorsportTracker/Schedule/Ui/ScheduleTitleUpcoming';
import ScheduleEventsList from '../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';

declare type SchedulePageProps = {
    events: EventsSchedule,
    firstDayString: number,
    lastDayString: number,
};

const SchedulePage: React.FunctionComponent<SchedulePageProps> = ({ firstDayString, lastDayString, events }) => {
    const [firstDay] = useState<Date>(new Date(firstDayString));
    const [lastDay] = useState<Date>(new Date(lastDayString));

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={(
                <ScheduleContainer>
                    <ScheduleTitleUpcoming />
                    <ScheduleEventsList events={events} firstDay={firstDay} lastDay={lastDay} />
                </ScheduleContainer>
            )}
        />
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
