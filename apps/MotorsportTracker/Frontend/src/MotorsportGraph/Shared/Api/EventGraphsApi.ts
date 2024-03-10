import { EventGraphs } from '../Types';

export type EventGraphsApi = (event: string) => Promise<EventGraphs>;

const eventGraphsApi: EventGraphsApi = async (event) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/graph/event/${event}`;
    console.log(`Fetching ${url}`);

    const configuration = {
        next: {
            tags: [`histories_${event}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as EventGraphs;
};

export default eventGraphsApi;
