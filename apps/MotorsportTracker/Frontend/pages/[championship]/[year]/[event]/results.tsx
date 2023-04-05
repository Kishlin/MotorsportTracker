// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import { EventShort, SeasonEvents } from '../../../../src/MotorsportTracker/Shared/Types';
import EventContainer from '../../../../src/MotorsportTracker/Event/Ui/EventContainer';
import EventNavbar from '../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import seasonApi from '../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import eventsApi from '../../../../src/MotorsportTracker/Event/Api/EventsApi';

declare type EventResultsPathParams = {
    params: {
        championship: string,
        year: string,
        event: string,
    },
};

declare type EventResultsPageProps = {
    championship: string,
    season: SeasonEvents,
    event: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<EventResultsPageProps> = ({
    championship,
    season,
    event,
    year,
    page,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <EventContainer>
                <p style={{ textAlign: 'center' }}>{`${championship} ${year} ${event} ${page}`}</p>
            </EventContainer>
        )}
        subHeader={<EventNavbar championship={championship} year={year} event={event} season={season} page={page} />}
    />
);

export const getStaticProps = async ({ params: { championship, year, event } }: EventResultsPathParams) => {
    const season = await seasonApi(championship, parseInt(year, 10));

    return {
        props: {
            championship,
            year,
            event,
            season,
            page: 'results',
        },
    };
};

export async function getStaticPaths(): Promise<{
    paths: Array<EventResultsPathParams>,
    fallback: boolean,
}> {
    const paths: Array<EventResultsPathParams> = [];

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
