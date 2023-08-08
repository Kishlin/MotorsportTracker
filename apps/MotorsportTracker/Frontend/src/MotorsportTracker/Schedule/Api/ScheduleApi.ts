import { EventsSchedule } from '../../Shared/Types';

export type ScheduleApi = (championship: string, year: string) => Promise<EventsSchedule>;

const scheduleApi: ScheduleApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/schedule/${championship}/${year}`);

    return await response.json() as EventsSchedule;
};

export default scheduleApi;
