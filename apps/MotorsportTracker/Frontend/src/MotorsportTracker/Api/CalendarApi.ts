import { EventsSchedule } from './Types';

export type CalendarApi = (firstDay: Date, lastDay: Date) => Promise<EventsSchedule>;

const format = (date: Date) => date.toISOString().split('T')[0];

const calendarApi: CalendarApi = async (firstDay, lastDay) => {
    const response = await fetch(`http://backend:8000/api/v1/calendar/view/${format(firstDay)}/${format(lastDay)}`);

    return await response.json() as EventsSchedule;
};

export default calendarApi;
