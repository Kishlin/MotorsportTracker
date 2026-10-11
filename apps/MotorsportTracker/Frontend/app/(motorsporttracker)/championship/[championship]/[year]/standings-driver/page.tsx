// noinspection JSUnusedGlobalSymbols

import ChampionshipContainer from '../../../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import driverStandingsApi from '../../../../../../src/MotorsportTracker/Standing/Api/DriverStandingsApi';
import StandingsContent from '../../../../../../src/MotorsportTracker/Standing/Ui/StandingsContent';

declare type PageParams = {
    championship: string,
    year: string,
};

const Page = async ({ params }: { params: Promise<PageParams> }) => {
    const { championship, year } = await params;

    const driverStandings = await driverStandingsApi(championship, year);

    return (
        <ChampionshipContainer>
            <StandingsContent standings={driverStandings.standings} type="driver" />
        </ChampionshipContainer>
    );
};

export default Page;
