// noinspection JSUnusedGlobalSymbols

import ChampionshipContainer from '../../../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import teamStandingsApi from '../../../../../../src/MotorsportTracker/Standing/Api/TeamStandingsApi';
import StandingsContent from '../../../../../../src/MotorsportTracker/Standing/Ui/StandingsContent';

declare type PageParams = {
    championship: string,
    year: string,
};

const Page = async ({ params: { championship, year } }: { params: PageParams }) => {
    const teamStandings = await teamStandingsApi(championship, year);

    return (
        <ChampionshipContainer>
            <StandingsContent standings={teamStandings.standings} type="team" />
        </ChampionshipContainer>
    );
};

export default Page;
