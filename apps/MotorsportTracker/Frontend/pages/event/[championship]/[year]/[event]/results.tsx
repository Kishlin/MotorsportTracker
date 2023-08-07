// @ts-ignore
import React from 'react';

import Layout from '../../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import ResultsContent from '../../../../../src/MotorsportTracker/Result/Ui/ResultsContent';
import EventContainer from '../../../../../src/MotorsportTracker/Event/Ui/EventContainer';
import { ResultsByRace } from '../../../../../src/MotorsportTracker/Result/Types/Index';
import EventNavbar from '../../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import resultsApi from '../../../../../src/MotorsportTracker/Result/Api/ResultsApi';
import EventTitle from '../../../../../src/MotorsportTracker/Event/Ui/EventTitle';
import seasonApi from '../../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import { SeasonEvents } from '../../../../../src/MotorsportTracker/Shared/Types';

declare type EventResultsPathParams = {
    params: {
        championship: string,
        year: string,
        event: string,
    },
};

declare type EventResultsPageProps = {
    championship: string,
    results: ResultsByRace,
    season: SeasonEvents,
    event: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<EventResultsPageProps> = ({
    championship,
    results,
    season,
    event,
    year,
    page,
}) => {
    if (undefined === results) {
        return null;
    }

    const withTitle = 1 < results.resultsByRace.length;

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={(
                <EventContainer>
                    <EventTitle event={season[event]} page={page} />
                    <ResultsContent results={results} withTitle={withTitle} event={season[event]} />
                </EventContainer>
            )}
            subHeader={(
                <EventNavbar championship={championship} year={year} event={event} season={season} page={page} />
            )}
        />
    );
};

export const getStaticProps = async ({ params: { championship, year, event } }: EventResultsPathParams) => {
    const season = await seasonApi(championship, parseInt(year, 10));

    const eventId = season[event].id;
    const results = await resultsApi(eventId);

    return {
        props: {
            championship,
            results,
            year,
            event,
            season,
            page: 'results',
        },
        revalidate: 60,
    };
};

export async function getStaticPaths(): Promise<{
    paths: Array<EventResultsPathParams>,
    fallback: boolean|'blocking',
}> {
    return { paths: [], fallback: 'blocking' };
}

export default ChampionshipStandingsPage;
