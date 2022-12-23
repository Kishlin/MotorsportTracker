import { EventCalendarMonth } from '../Types';

export type CalendarApi = (month: string, year: string) => Promise<EventCalendarMonth>;

const calendarApi: CalendarApi = async (month, year) => {
    const response = await fetch(`http://backend:8000/api/v1/events/calendar/${month}/${year}`);

    return await response.json() as EventCalendarMonth;
};

export default calendarApi;
