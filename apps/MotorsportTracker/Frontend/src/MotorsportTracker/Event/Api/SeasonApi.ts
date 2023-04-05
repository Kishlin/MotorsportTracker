import { SeasonEvents } from '../../Shared/Types';

export type SeasonApi = (championshipSlug: string, year: number) => Promise<SeasonEvents>;

const seasonApi: SeasonApi = async (championshipSlug, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/events/${championshipSlug}/${year}`);

    return await response.json() as SeasonEvents;
};

export default seasonApi;
