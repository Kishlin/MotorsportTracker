import { ConstructorAnalytics } from '../Types/Index';

export type ConstructorsAnalyticsApi = (championship: string, year: string) => Promise<Array<ConstructorAnalytics>>;

const constructorsAnalyticsApi: ConstructorsAnalyticsApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/analytics/${championship}/${year}/constructors`);

    try {
        return await response.json() as Array<ConstructorAnalytics>;
    } catch (err) {
        return [];
    }
};

export default constructorsAnalyticsApi;
