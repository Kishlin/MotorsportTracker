import { ResultsBySession } from '../Types/Index';

export type ResultsApi = (event: string) => Promise<ResultsBySession>;

const resultsApi: ResultsApi = async (event) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/results/${event}`);

    return await response.json() as ResultsBySession;
};

export default resultsApi;
