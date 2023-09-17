import { DriverAnalytics } from '../Types/Index';

export type DriversAnalyticsApi = (championship: string, year: string) => Promise<Array<DriverAnalytics>>;

const driversAnalyticsApi: DriversAnalyticsApi = async (championship, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/analytics/${championship}/${year}/drivers`;

    const configuration = {
        next: {
            tags: [`analytics-${championship}-${year}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    if (404 === response.status) {
        return [];
    }

    return await response.json() as Array<DriverAnalytics>;
};

export default driversAnalyticsApi;
