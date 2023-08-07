// @ts-ignore
import React from 'react';
import Typography from '@mui/material/Typography';

import Layout from '../../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import EventContainer from '../../../../../src/MotorsportTracker/Event/Ui/EventContainer';
import EventNavbar from '../../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import seasonApi from '../../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import { SeasonEvents } from '../../../../../src/MotorsportTracker/Shared/Types';

declare type EventHistoriesPathParams = {
    params: {
        championship: string,
        year: string,
        event: string,
    },
};

declare type EventHistoriesPageProps = {
    championship: string,
    season: SeasonEvents,
    event: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<EventHistoriesPageProps> = ({
    championship,
    season,
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
                <EventContainer>
                    <Typography variant="h4" align="left" sx={{ my: 4 }}>{`${season[event].name} - Graphs`}</Typography>
                    <Typography align="center">There are no histories available at this time.</Typography>
                </EventContainer>
            )}
            subHeader={
                <EventNavbar championship={championship} year={year} event={event} season={season} page={page} />
            }
        />
    );
};

export const getStaticProps = async ({ params: { championship, year, event } }: EventHistoriesPathParams) => {
    const season = await seasonApi(championship, parseInt(year, 10));

    return {
        props: {
            championship,
            year,
            event,
            season,
            page: 'histories',
        },
        revalidate: 60,
    };
};

export async function getStaticPaths(): Promise<{
    paths: Array<EventHistoriesPathParams>,
    fallback: boolean|'blocking',
}> {
    return { paths: [], fallback: 'blocking' };
}

export default ChampionshipStandingsPage;
