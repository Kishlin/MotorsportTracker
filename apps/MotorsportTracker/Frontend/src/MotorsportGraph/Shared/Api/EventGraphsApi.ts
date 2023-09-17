import { EventGraphs } from '../Types';

export type EventGraphsApi = (event: string) => Promise<EventGraphs>;

const eventGraphsApi: EventGraphsApi = async (event) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/graph/event/${event}`;

    const configuration = {
        next: {
            tags: [`graphs-${event}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as EventGraphs;
};

export default eventGraphsApi;
