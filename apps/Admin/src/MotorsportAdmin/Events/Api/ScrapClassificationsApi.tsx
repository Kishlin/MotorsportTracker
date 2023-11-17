export type ScrapClassificationsApi = (seriesId: string, year: number, event: string) => Promise<{ uuid: string }>;

const scrapClassificationsApi: ScrapClassificationsApi = async (seriesId: string, year: number, event: string) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/classification/${seriesId}/${year}/${event}`,
        {
            method: 'POST',
            cache: 'no-store',
        },
    );

    return await response.json() as { uuid: string };
};

export default scrapClassificationsApi;
