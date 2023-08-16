import cacheData from 'memory-cache';

import { AvailableStandings } from '../../Shared/Types';

export type AvailableStandingApi = (championship: string, year: string) => Promise<AvailableStandings>;

const availableStandingApi: AvailableStandingApi = async (championship, year) => {
    const key = `standings-options-${championship}-${year}`;

    const cached = cacheData.get(key);
    if (cached) {
        return cached;
    }

    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/options/${championship}/${year}`);

    const value = await response.json() as AvailableStandings;

    cacheData.put(key, value, 3600000);

    return value;
};

export default availableStandingApi;
