// @ts-ignore
import React from 'react';

import ScheduleTitleUpcoming from '../src/MotorsportTracker/Schedule/Ui/ScheduleTitleUpcoming';
import MotorsportTrackerMenu from '../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import ScheduleEventsList from '../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import ScheduleContainer from '../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import upcomingApi from '../src/MotorsportTracker/Schedule/Api/UpcomingApi';
import { EventsSchedule } from '../src/MotorsportTracker/Shared/Types';
import Layout from '../src/Shared/Ui/Layout/Layout';

declare type SchedulePageProps = {
    events: EventsSchedule,
};

const SchedulePage: React.FunctionComponent<SchedulePageProps> = ({ events }) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ScheduleContainer>
                <ScheduleTitleUpcoming />
                <ScheduleEventsList events={events} />
            </ScheduleContainer>
        )}
    />
);

export async function getServerSideProps(): Promise<{ props: SchedulePageProps }> {
    const events = await upcomingApi();

    return {
        props: {
            events,
        },
    };
}

export default SchedulePage;
