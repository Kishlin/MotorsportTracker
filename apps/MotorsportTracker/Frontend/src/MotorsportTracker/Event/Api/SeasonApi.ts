import cacheData from 'memory-cache';

import { SeasonEvents } from '../../Shared/Types';

export type SeasonApi = (championshipSlug: string, year: number) => Promise<SeasonEvents>;

const seasonApi: SeasonApi = async (championshipSlug, year) => {
    const key = `season-${championshipSlug}-${year}`;

    const cached = cacheData.get(key);
    if (cached) {
        return cached;
    }

    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/events/${championshipSlug}/${year}`);

    const value = (await response.json() as SeasonEvents);

    cacheData.put(key, value, 3600000);

    return value;
};

export default seasonApi;
