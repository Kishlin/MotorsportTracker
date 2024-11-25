// noinspection JSUnusedGlobalSymbols

import constructorsAnalyticsApi from '../../../../../../src/MotorsportTracker/Analytics/Api/ConstructorsAnalyticsApi';
import ChampionshipContainer from '../../../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import driversAnalyticsApi from '../../../../../../src/MotorsportTracker/Analytics/Api/DriversAnalyticsApi';
import teamsAnalyticsApi from '../../../../../../src/MotorsportTracker/Analytics/Api/TeamsAnalyticsApi';
import AnalyticsContent from '../../../../../../src/MotorsportTracker/Analytics/Ui/AnalyticsContent';

declare type PageParams = {
    championship: string,
    year: string,
};

const Page = async (props: { params: Promise<PageParams> }) => {
    const { championship, year } = await props.params;

    const constructorsAnalytics = await constructorsAnalyticsApi(championship, year);
    const driversAnalytics = await driversAnalyticsApi(championship, year);
    const teamsAnalytics = await teamsAnalyticsApi(championship, year);

    return (
        <ChampionshipContainer>
            <AnalyticsContent
                constructorsAnalytics={constructorsAnalytics}
                driversAnalytics={driversAnalytics}
                teamsAnalytics={teamsAnalytics}
            />
        </ChampionshipContainer>
    );
};

export default Page;
