import { DriverAnalytics } from '../Types/Index';

export type DriversAnalyticsApi = (championship: string, year: string) => Promise<Array<DriverAnalytics>>;

const driversAnalyticsApi: DriversAnalyticsApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/analytics/${championship}/${year}/drivers`);

    try {
        return await response.json() as Array<DriverAnalytics>;
    } catch (err) {
        return [];
    }
};

export default driversAnalyticsApi;
