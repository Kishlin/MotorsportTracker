import { AvailableStandings } from '../../Shared/Types';

export type AvailableStandingApi = (championship: string, year: string) => Promise<AvailableStandings>;

const availableStandingApi: AvailableStandingApi = async (championship, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/options/${championship}/${year}`;
    console.log(`Fetching ${url}`);

    const configuration = {
        next: {
            tags: [`stats_${championship}_${year}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as AvailableStandings;
};

export default availableStandingApi;
