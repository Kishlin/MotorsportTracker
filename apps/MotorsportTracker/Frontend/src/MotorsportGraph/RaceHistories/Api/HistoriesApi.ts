import { HistoriesList } from '../Types';

export type HistoriesApi = (event: string) => Promise<HistoriesList>;

const historiesApi: HistoriesApi = async (event) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/histories/${event}`;
    console.log(`Fetching ${url}`);

    const configuration = {
        next: {
            tags: [`histories_${event}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as HistoriesList;
};

export default historiesApi;
