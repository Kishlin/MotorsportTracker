export type ScrapCalendarApi = (seriesId: string, year: number) => Promise<{ uuid: string }>;

const scrapCalendarApi: ScrapCalendarApi = async (seriesId: string, year: number) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/calendar/${seriesId}/${year}`,
        {
            method: 'POST',
            cache: 'no-store',
        },
    );

    return await response.json() as { uuid: string };
};

export default scrapCalendarApi;
