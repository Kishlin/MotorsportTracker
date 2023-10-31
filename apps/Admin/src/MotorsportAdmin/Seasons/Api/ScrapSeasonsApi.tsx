export type ScrapSeasonsApi = (seriesId: string) => Promise<{ uuid: string }>;

const scrapSeasonsApi: ScrapSeasonsApi = async (seriesId: string) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/seasons/${seriesId}`,
        {
            method: 'POST',
            cache: 'no-store',
        },
    );

    return await response.json() as { uuid: string };
};

export default scrapSeasonsApi;
