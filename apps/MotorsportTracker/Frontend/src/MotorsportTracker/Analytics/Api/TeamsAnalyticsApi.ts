import { TeamAnalytics } from '../Types/Index';

export type TeamsAnalyticsApi = (championship: string, year: string) => Promise<Array<TeamAnalytics>>;

const teamsAnalyticsApi: TeamsAnalyticsApi = async (championship, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/analytics/${championship}/${year}/teams`;
    console.log(`Fetching ${url}`);

    const configuration = {
        next: {
            tags: [`stats_${championship}_${year}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    if (404 === response.status) {
        return [];
    }

    return await response.json() as Array<TeamAnalytics>;
};

export default teamsAnalyticsApi;
