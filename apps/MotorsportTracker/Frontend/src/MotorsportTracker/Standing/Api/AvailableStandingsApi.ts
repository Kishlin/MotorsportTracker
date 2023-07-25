import { AvailableStandings } from '../../Shared/Types';

export type AvailableStandingApi = (championship: string, year: string) => Promise<AvailableStandings>;

const availableStandingApi: AvailableStandingApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/options/${championship}/${year}`);

    return await response.json() as AvailableStandings;
};

export default availableStandingApi;
