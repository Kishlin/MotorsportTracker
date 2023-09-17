import { ConstructorAnalytics } from '../Types/Index';

export type ConstructorsAnalyticsApi = (championship: string, year: string) => Promise<Array<ConstructorAnalytics>>;

const constructorsAnalyticsApi: ConstructorsAnalyticsApi = async (championship, year) => {
    const url = `${process.env.NEXT_PUBLIC_API_URL}/api/v1/analytics/${championship}/${year}/constructors`;

    const configuration = {
        next: {
            tags: [`standings-${championship}-${year}`],
        },
    };

    const response = await fetch(url, configuration as RequestInit);

    if (404 === response.status) {
        return [];
    }

    return await response.json() as Array<ConstructorAnalytics>;
};

export default constructorsAnalyticsApi;
