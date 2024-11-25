// noinspection JSUnusedGlobalSymbols

import ChampionshipContainer from '../../../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ScheduleEventsList from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import scheduleApi from '../../../../../../src/MotorsportTracker/Schedule/Api/ScheduleApi';

declare type PageParams = {
    championship: string,
    year: string,
};

const Page = async (props: { params: Promise<PageParams> }) => {
    const { championship, year } = await props.params;

    const schedule = await scheduleApi(championship, year);

    return (
        <ChampionshipContainer>
            <ScheduleEventsList events={schedule} />
        </ChampionshipContainer>
    );
};

export default Page;
