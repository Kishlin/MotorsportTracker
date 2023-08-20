// noinspection JSUnusedGlobalSymbols

import { Suspense } from 'react';
import { Metadata } from 'next';

import listOfAllMonths from '../../../../../../src/MotorsportTracker/Schedule/Utils/Date/listOfAllMonths';
import ScheduleEventsList from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import ScheduleNavigation from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleNavigation';
import ScheduleContainer from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import ScheduleSkeleton from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleSkeleton';
import ScheduleTitle from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleTitle';
import calendarApi from '../../../../../../src/MotorsportTracker/Schedule/Api/CalendarApi';

declare type PageParams = {
    month: string,
    year: string,
};

export async function generateMetadata({ params: { month, year } }: { params: PageParams }): Promise<Metadata> {
    return {
        title: `Schedule - ${month.slice(0, 1).toUpperCase()}${month.slice(1)} ${year} - Motorsport Tracker`,
    };
}

const Page = async ({ params: { month, year } }: { params: PageParams }) => {
    const events = await calendarApi(month, year);

    const date = new Date(Date.parse(`${month} 1, ${year}`)).getTime();

    return (
        <ScheduleContainer>
            <ScheduleTitle />
            <ScheduleNavigation date={new Date(date)} />
            <Suspense fallback={<ScheduleSkeleton />}>
                <ScheduleEventsList events={events} />
            </Suspense>
        </ScheduleContainer>
    );
};

export function generateStaticParams(): Array<PageParams> {
    const paths: Array<PageParams> = [];

    for (let year = 2020; 2023 >= year; year += 1) {
        paths.push(...listOfAllMonths().map((month: string) => ({ year: year.toString(), month })));
    }

    return paths;
}

export const dynamicParams = true;

export default Page;
