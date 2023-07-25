import { ConstructorStanding } from '../../Shared/Types';
import { List } from '../../../Shared/Types/Index';

export type ConstructorStandingApi = (championship: string, year: string) =>
    Promise<{ standings: List<Array<ConstructorStanding>> }>;

const constructorStandingApi: ConstructorStandingApi = async (championship, year) => {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/details/${championship}/${year}/constructor`);

    return await response.json() as { standings: List<Array<ConstructorStanding>> };
};

export default constructorStandingApi;
