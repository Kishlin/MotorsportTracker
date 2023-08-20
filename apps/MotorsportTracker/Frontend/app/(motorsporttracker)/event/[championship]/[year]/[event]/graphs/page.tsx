// noinspection JSUnusedGlobalSymbols

import eventGraphsApi from '../../../../../../../src/MotorsportGraph/Shared/Api/EventGraphsApi';
import GraphContainer from '../../../../../../../src/MotorsportGraph/Shared/Ui/GraphContainer';
import championships from '../../../../../../../src/MotorsportTracker/Config/Championships';
import seasonApi from '../../../../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import GraphTitle from '../../../../../../../src/MotorsportGraph/Shared/Ui/GraphTitle';
import Graphs from '../../../../../../../src/MotorsportGraph/Shared/Ui/Graphs';

declare type PageParams = {
    championship: string,
    event: string,
    year: string,
};

const Page = async ({ params: { championship, year, event } }: { params: PageParams }) => {
    const { isMultiDriver } = championships[championship];

    const season = await seasonApi(championship, parseInt(year, 10));

    const graphs = await eventGraphsApi(season[event].id);

    return (
        <GraphContainer>
            <GraphTitle event={season[event]} />
            <Graphs graphs={graphs} isMultiDriver={isMultiDriver} />
        </GraphContainer>
    );
};

export default Page;
