export type ScrapStandingsApi = (seriesId: string, year: number) => Promise<{ uuid: string }>;

const scrapStandingsApi: ScrapStandingsApi = async (seriesId: string, year: number) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/standings/${seriesId}/${year}`,
        {
            method: 'POST',
            cache: 'no-store',
        },
    );

    return await response.json() as { uuid: string };
};

export default scrapStandingsApi;
