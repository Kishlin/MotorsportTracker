import { Series } from '../../Shared/Types';

export type SeriesApi = () => Promise<Array<Series>>;

const seriesApi: SeriesApi = async () => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/series/`,
        {
            cache: 'no-store',
        },
    );

    return await response.json() as Array<Series>;
};

export default seriesApi;
