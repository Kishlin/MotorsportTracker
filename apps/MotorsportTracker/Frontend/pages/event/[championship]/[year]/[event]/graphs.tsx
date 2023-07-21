// @ts-ignore
import React from 'react';

import MotorsportTrackerMenu from '../../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import { EventShort, SeasonEvents } from '../../../../../src/MotorsportTracker/Shared/Types';
import eventGraphsApi from '../../../../../src/MotorsportGraph/Shared/Api/EventGraphsApi';
import GraphContainer from '../../../../../src/MotorsportGraph/Shared/Ui/GraphContainer';
import EventNavbar from '../../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import seasonApi from '../../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import eventsApi from '../../../../../src/MotorsportTracker/Event/Api/EventsApi';
import GraphTitle from '../../../../../src/MotorsportGraph/Shared/Ui/GraphTitle';
import { EventGraphs } from '../../../../../src/MotorsportGraph/Shared/Types';
import Graphs from '../../../../../src/MotorsportGraph/Shared/Ui/Graphs';
import Layout from '../../../../../src/Shared/Ui/Layout/Layout';

declare type EventGraphsPathParams = {
    params: {
        championship: string,
        year: string,
        event: string,
    },
};

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

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={(
                <GraphContainer>
                    <GraphTitle event={season[event]} />
                    <Graphs graphs={graphs} />
                </GraphContainer>
            )}
            subHeader={
                <EventNavbar championship={championship} year={year} event={event} season={season} page={page} />
            }
        />
    );
};

export const getStaticProps = async ({ params: { championship, year, event } }: EventGraphsPathParams) => {
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
    };
};

export async function getStaticPaths(): Promise<{
    paths: Array<EventGraphsPathParams>,
    fallback: boolean,
}> {
    const paths: Array<EventGraphsPathParams> = [];

    const events = await eventsApi();

    events.forEach((event: EventShort) => {
        paths.push({
            params: {
                ...event,
                year: event.year.toString(),
            },
        });
    });

    return { paths, fallback: false };
}

export default ChampionshipStandingsPage;
