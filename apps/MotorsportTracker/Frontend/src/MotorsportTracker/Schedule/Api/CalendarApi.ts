import { EventsSchedule } from '../../Shared/Types';

export type CalendarApi = (month: string, year: string) => Promise<EventsSchedule>;

const calendarApi: CalendarApi = async (month, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/calendar/${month}/${year}`);

    return await response.json() as EventsSchedule;
};

export default calendarApi;
