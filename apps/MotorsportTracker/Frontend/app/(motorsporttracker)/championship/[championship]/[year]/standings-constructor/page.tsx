// noinspection JSUnusedGlobalSymbols

import constructorStandingsApi from '../../../../../../src/MotorsportTracker/Standing/Api/ConstructorStandingsApi';
import ChampionshipContainer from '../../../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import StandingsContent from '../../../../../../src/MotorsportTracker/Standing/Ui/StandingsContent';

declare type PageParams = {
    championship: string,
    year: string,
};

const Page = async (props: { params: Promise<PageParams> }) => {
    const { championship, year } = await props.params;

    const constructorStandings = await constructorStandingsApi(championship, year);

    return (
        <ChampionshipContainer>
            <StandingsContent standings={constructorStandings.standings} type="constructor" />
        </ChampionshipContainer>
    );
};

export default Page;
