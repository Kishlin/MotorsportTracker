// noinspection JSUnusedGlobalSymbols

import HistoriesContainer from '../../../../../../../src/MotorsportGraph/RaceHistories/Ui/HistoriesContainer';
import HistoriesListTitle from '../../../../../../../src/MotorsportGraph/RaceHistories/Ui/HistoriesListTitle';
import historiesApi from '../../../../../../../src/MotorsportGraph/RaceHistories/Api/HistoriesApi';
import championships from '../../../../../../../src/MotorsportTracker/Config/Championships';
import Histories from '../../../../../../../src/MotorsportGraph/RaceHistories/Ui/Histories';
import seasonApi from '../../../../../../../src/MotorsportTracker/Event/Api/SeasonApi';

declare type PageParams = {
    championship: string,
    event: string,
    year: string,
};

const Page = async ({ params: { championship, year, event } }: { params: PageParams }) => {
    const { isMultiDriver } = championships[championship];

    const season = await seasonApi(championship, parseInt(year, 10));

    const histories = await historiesApi(season[event].id);

    return (
        <HistoriesContainer>
            <HistoriesListTitle eventName={season[event].name} />
            <Histories histories={histories} isMultiDriver={isMultiDriver} />
        </HistoriesContainer>
    );
};

export default Page;
