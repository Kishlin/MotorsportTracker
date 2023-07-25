import { TeamStanding } from '../../Shared/Types';
import { List } from '../../../Shared/Types/Index';

export type TeamStandingApi = (championship: string, year: string) => Promise<{ standings: List<Array<TeamStanding>> }>;

const teamStandingApi: TeamStandingApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/details/${championship}/${year}/team`);

    return await response.json() as { standings: List<Array<TeamStanding>> };
};

export default teamStandingApi;
