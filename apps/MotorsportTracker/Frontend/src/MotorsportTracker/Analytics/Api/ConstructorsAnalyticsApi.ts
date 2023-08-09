import { ConstructorAnalytics } from '../Types/Index';

export type ConstructorsAnalyticsApi = (championship: string, year: string) => Promise<Array<ConstructorAnalytics>>;

const constructorsAnalyticsApi: ConstructorsAnalyticsApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/analytics/${championship}/${year}/constructors`);

    if (404 === response.status) {
        return [];
    }

    return await response.json() as Array<ConstructorAnalytics>;
};

export default constructorsAnalyticsApi;
