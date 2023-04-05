import { EventsList } from '../../Shared/Types';

export type EventsApi = () => Promise<EventsList>;

const eventsApi: EventsApi = async () => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/events/`);

    return await response.json() as EventsList;
};

export default eventsApi;
