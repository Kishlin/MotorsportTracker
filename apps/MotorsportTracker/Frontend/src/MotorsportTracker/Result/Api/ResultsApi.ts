import { ResultsBySession } from '../Types/Index';

export type ResultsApi = (event: string) => Promise<ResultsBySession>;

const resultsApi: ResultsApi = async (event) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/results/${event}`;

    const configuration = {
        next: {
            tags: [`classifications_${event}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as ResultsBySession;
};

export default resultsApi;
