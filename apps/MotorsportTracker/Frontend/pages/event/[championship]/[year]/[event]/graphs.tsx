// @ts-ignore
import React from 'react';

import MotorsportTrackerMenu from '../../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import eventGraphsApi from '../../../../../src/MotorsportGraph/Shared/Api/EventGraphsApi';
import GraphContainer from '../../../../../src/MotorsportGraph/Shared/Ui/GraphContainer';
import EventNavbar from '../../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import seasonApi from '../../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import GraphTitle from '../../../../../src/MotorsportGraph/Shared/Ui/GraphTitle';
import { SeasonEvents } from '../../../../../src/MotorsportTracker/Shared/Types';
import { EventGraphs } from '../../../../../src/MotorsportGraph/Shared/Types';
import Graphs from '../../../../../src/MotorsportGraph/Shared/Ui/Graphs';
import Layout from '../../../../../src/Shared/Ui/Layout/Layout';
import championships from '../../../../../src/MotorsportTracker/Config/Championships';
import {
    EventPathParams,
    useEventStaticPaths,
} from '../../../../../src/MotorsportTracker/Shared/Hook/EventStaticPaths';

declare type EventGraphsPageProps = {
    championship: string,
    season: SeasonEvents,
    graphs: EventGraphs,
    event: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<EventGraphsPageProps> = ({
    championship,
    season,
    graphs,
    event,
    year,
    page,
}) => {
    if (undefined === season) {
        return null;
    }

    const { isMultiDriver } = championships[championship];

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={(
                <GraphContainer>
                    <GraphTitle event={season[event]} />
                    <Graphs graphs={graphs} isMultiDriver={isMultiDriver} />
                </GraphContainer>
            )}
            subHeader={
                <EventNavbar championship={championship} year={year} event={event} season={season} page={page} />
            }
        />
    );
};

export const getStaticProps = async ({ params: { championship, year, event } }: EventPathParams) => {
    const season = await seasonApi(championship, parseInt(year, 10));

    const graphs = await eventGraphsApi(season[event].id);

    return {
        props: {
            championship,
            year,
            event,
            season,
            graphs,
            page: 'graphs',
        },
        revalidate: 86400,
    };
};

export async function getStaticPaths(): Promise<{
    paths: Array<EventPathParams>,
    fallback: boolean|'blocking',
}> {
    const eventStaticPaths = useEventStaticPaths();

    return eventStaticPaths();
}

export default ChampionshipStandingsPage;
