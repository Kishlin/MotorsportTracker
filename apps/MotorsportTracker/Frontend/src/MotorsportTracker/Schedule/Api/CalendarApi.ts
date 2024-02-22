import { EventsSchedule } from '../../Shared/Types';

export type CalendarApi = (month: string, year: string) => Promise<EventsSchedule>;

const calendarApi: CalendarApi = async (month, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/calendar/${month}/${year}`;
    console.log(`Fetching ${url}`);

    const configuration = {
        next: {
            tags: [year],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as EventsSchedule;
};

export default calendarApi;
