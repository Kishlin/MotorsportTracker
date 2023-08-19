// noinspection JSUnusedGlobalSymbols

import ChampionshipContainer from '../../../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ScheduleEventsList from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleEventsList';
import scheduleApi from '../../../../../../src/MotorsportTracker/Schedule/Api/ScheduleApi';
import championships from '../../../../../../src/MotorsportTracker/Config/Championships';

declare type PageParams = {
    championship: string,
    year: string,
};

const Page = async ({ params: { championship, year }}: { params: PageParams }) => {
    const schedule = await scheduleApi(championship, year);

    return (
        <ChampionshipContainer>
            <ScheduleEventsList events={schedule} />
        </ChampionshipContainer>
    );
};

export function generateStaticParams(): Array<PageParams> {
    const paths: Array<PageParams> = [];

    Object.entries(championships).forEach(([slug, championship]) => {
        championship.years.forEach((year: number) => {
            paths.push({ championship: slug, year: year.toString() });
        });
    });

    return paths;
}
export const dynamicParams = true;

export default Page;
