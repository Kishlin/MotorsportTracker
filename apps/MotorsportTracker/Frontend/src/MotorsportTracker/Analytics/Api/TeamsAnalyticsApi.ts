import { TeamAnalytics } from '../Types/Index';

export type TeamsAnalyticsApi = (championship: string, year: string) => Promise<Array<TeamAnalytics>>;

const teamsAnalyticsApi: TeamsAnalyticsApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/analytics/${championship}/${year}/teams`);

    try {
        return await response.json() as Array<TeamAnalytics>;
    } catch (err) {
        return [];
    }
};

export default teamsAnalyticsApi;
