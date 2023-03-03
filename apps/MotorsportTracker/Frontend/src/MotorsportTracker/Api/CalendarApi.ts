import { EventsSchedule } from '../Shared/Types';

export type CalendarApi = (firstDay: Date, lastDay: Date) => Promise<EventsSchedule>;

const format = (date: Date) => date.toISOString().split('T')[0];

const calendarApi: CalendarApi = async (firstDay, lastDay) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/calendar/view/${format(firstDay)}/${format(lastDay)}`);

    return await response.json() as EventsSchedule;
};

export default calendarApi;
