import { Event } from '../../Shared/Types';

export type EventsListApi = (seriesName: string, year: number) => Promise<Array<Event>>;

const eventsListApi: EventsListApi = async (seriesName: string, year: number) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/events/${seriesName}/${year}`,
        {
            cache: 'no-store',
        },
    );

    return await response.json() as Array<Event>;
};

export default eventsListApi;
