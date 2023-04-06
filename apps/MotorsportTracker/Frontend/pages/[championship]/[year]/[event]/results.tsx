// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import ResultsContainer from '../../../../src/MotorsportTracker/Result/Ui/ResultsContainer';
import { EventShort, SeasonEvents } from '../../../../src/MotorsportTracker/Shared/Types';
import ResultsContent from '../../../../src/MotorsportTracker/Result/Ui/ResultsContent';
import { ResultsByRace } from '../../../../src/MotorsportTracker/Result/Types/Index';
import ResultsTitle from '../../../../src/MotorsportTracker/Result/Ui/ResultsTitle';
import EventNavbar from '../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import resultsApi from '../../../../src/MotorsportTracker/Result/Api/ResultsApi';
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
    const withTitle = 1 < results.resultsByRace.length;

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={(
                <ResultsContainer>
                    <ResultsTitle event={season[event]} />
                    <ResultsContent results={results} withTitle={withTitle} event={season[event]} />
                </ResultsContainer>
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
