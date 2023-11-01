import { Season } from '../../Shared/Types';

export type SeasonsListApi = (seriesName: string) => Promise<Array<Season>>;

const seasonsListApi: SeasonsListApi = async (seriesName: string) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/seasons/${seriesName}`,
        {
            cache: 'no-store',
        },
    );

    return await response.json() as Array<Season>;
};

export default seasonsListApi;
