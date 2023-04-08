import { EventGraphs } from '../Types';

export type EventGraphsApi = (event: string) => Promise<EventGraphs>;

const eventGraphsApi: EventGraphsApi = async (event) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/graph/event/${event}`);

    return await response.json() as EventGraphs;
};

export default eventGraphsApi;
