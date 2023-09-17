import { EventsSchedule } from '../../Shared/Types';

export type ScheduleApi = (championship: string, year: string) => Promise<EventsSchedule>;

const scheduleApi: ScheduleApi = async (championship, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/schedule/${championship}/${year}`;

    const configuration = {
        next: {
            tags: [`schedule-${championship}-${year}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as EventsSchedule;
};

export default scheduleApi;
