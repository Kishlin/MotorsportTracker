import { EventsSchedule } from '../../Shared/Types';

export type UpcomingApi = () => Promise<EventsSchedule>;

const upcomingApi: UpcomingApi = async () => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_API_URL}/api/v1/calendar/upcoming`,
        {
            cache: 'no-store',
        },
    );

    return await response.json() as EventsSchedule;
};

export default upcomingApi;
