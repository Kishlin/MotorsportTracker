import { HistoriesList } from '../Types';

export type HistoriesApi = (event: string) => Promise<HistoriesList>;

const historiesApi: HistoriesApi = async (event) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/histories/${event}`);

    return await response.json() as HistoriesList;
};

export default historiesApi;
