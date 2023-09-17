import { SeasonEvents } from '../../Shared/Types';

export type SeasonApi = (championshipSlug: string, year: number) => Promise<SeasonEvents>;

const seasonApi: SeasonApi = async (championshipSlug, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/events/${championshipSlug}/${year}`;

    const configuration = {
        next: {
            tags: [`events-${championshipSlug}-${year}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as SeasonEvents;
};

export default seasonApi;
