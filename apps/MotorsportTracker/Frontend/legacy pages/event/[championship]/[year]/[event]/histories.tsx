// @ts-ignore
import React from 'react';
import Typography from '@mui/material/Typography';

import Layout from '../../../../../src/Shared/Ui/Layout/Layout';

import HistoriesContainer from '../../../../../src/MotorsportGraph/RaceHistories/Ui/HistoriesContainer';
import MotorsportTrackerMenu from '../../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import historiesApi from '../../../../../src/MotorsportGraph/RaceHistories/Api/HistoriesApi';
import { HistoriesList } from '../../../../../src/MotorsportGraph/RaceHistories/Types';
import championships from '../../../../../src/MotorsportTracker/Config/Championships';
import EventNavbar from '../../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import seasonApi from '../../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import { SeasonEvents } from '../../../../../src/MotorsportTracker/Shared/Types';
import Histories from '../../../../../src/MotorsportGraph/RaceHistories/Ui/Histories';
import {
    EventPathParams,
    useEventStaticPaths,
} from '../../../../../src/MotorsportTracker/Shared/Hook/EventStaticPaths';

declare type EventHistoriesPageProps = {
    championship: string,
    season: SeasonEvents,
    histories: HistoriesList,
    event: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<EventHistoriesPageProps> = ({
    championship,
    histories,
    season,
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
                <HistoriesContainer>
                    <Typography variant="h4" align="left" sx={{ my: 4 }}>{`${season[event].name} - Histories`}</Typography>
                    <Histories histories={histories} isMultiDriver={isMultiDriver} />
                </HistoriesContainer>
            )}
            subHeader={
                <EventNavbar championship={championship} year={year} event={event} season={season} page={page} />
            }
        />
    );
};

export const getStaticProps = async ({ params: { championship, year, event } }: EventPathParams) => {
    const season = await seasonApi(championship, parseInt(year, 10));

    const histories = await historiesApi(season[event].id);

    return {
        props: {
            championship,
            year,
            event,
            season,
            histories,
            page: 'histories',
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
