import { Suspense } from 'react';
import { Metadata } from 'next';

import ScheduleTitleUpcoming from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleTitleUpcoming';
import ScheduleEventsList from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import ScheduleContainer from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import ScheduleSkeleton from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleSkeleton';
import upcomingApi from '../../../src/MotorsportTracker/Schedule/Api/UpcomingApi';

async function loadEvents() {
    return upcomingApi();
}

export async function generateMetadata(): Promise<Metadata> {
    return {
        title: 'Upcoming - Motorsport Tracker',
    };
}

const Page = async () => {
    const events = await loadEvents();

    return (
        <ScheduleContainer>
            <ScheduleTitleUpcoming />
            <Suspense fallback={<ScheduleSkeleton />}>
                <ScheduleEventsList events={events} />
            </Suspense>
        </ScheduleContainer>
    );
};

export default Page;
