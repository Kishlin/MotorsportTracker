import { DriverStanding } from '../../Shared/Types';
import { List } from '../../../Shared/Types/Index';

export type DriverStandingApi = (championship: string, year: string) =>
    Promise<{ standings: List<Array<DriverStanding>> }>;

const driverStandingApi: DriverStandingApi = async (championship, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/details/${championship}/${year}/driver`;

    const configuration = {
        next: {
            tags: [`stats_${championship}_${year}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    return await response.json() as { standings: List<Array<DriverStanding>> };
};

export default driverStandingApi;
