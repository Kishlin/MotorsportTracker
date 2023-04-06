import { ResultsByRace } from '../Types/Index';

export type ResultsApi = (event: string) => Promise<ResultsByRace>;

const resultsApi: ResultsApi = async (event) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/results/${event}`);

    return await response.json() as ResultsByRace;
};

export default resultsApi;
