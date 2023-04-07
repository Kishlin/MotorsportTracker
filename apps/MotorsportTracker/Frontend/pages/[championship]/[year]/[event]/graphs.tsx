// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import LapByLapGraph from '../../../../src/MotorsportChart/LapByLapRacePace/Ui/LapByLapGraph';
import { EventShort, SeasonEvents } from '../../../../src/MotorsportTracker/Shared/Types';
import EventContainer from '../../../../src/MotorsportTracker/Event/Ui/EventContainer';
import EventNavbar from '../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import seasonApi from '../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import eventsApi from '../../../../src/MotorsportTracker/Event/Api/EventsApi';
import EventTitle from '../../../../src/MotorsportTracker/Event/Ui/EventTitle';

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
    event: string,
    year: string,
    page: string,
}

const lapTimes = [
        85542,
        85348,
        85218,
        85318,
        85168,
        84948,
        85067,
        84987,
        84757,
        84846,
        83957,
        84027,
        83842,
        83627,
        84624,
        84548,
        84357,
        83726,
        83489,
        83527,
        83246,
        84426,
        84189,
        84248,
        83357,
        83186,
        83084,
        82928,
        83489,
        82758,
        82846,
        82657,
        82542,
        82318,
        82483,
        82189,
        82248,
        81842,
        81987,
        82081,
        81642,
        81726,
        81489,
        81573,
        81384,
        81145,
        81248,
        81073,
        80927,
        80874,
        80648,
        80427,
        80738,
        80591,
        80234,
        80348,
    ];

const data = {
    laps: 56,
    lapTimes: {
        fastest: 80234,
        slowest: 85860,
    },
    series: [
        {
            color: '#ff0000',
            label: 'VER',
            dashed: false,
            lapTimes,
        },
        {
            color: '#ff0000',
            label: 'PER',
            dashed: true,
            lapTimes,
        },
        {
            color: '#dd777b',
            label: 'LEC',
            dashed: false,
            lapTimes,
        },
        {
            color: '#dd777b',
            label: 'SAI',
            dashed: true,
            lapTimes,
        },
        {
            color: '#5bd8cb',
            label: 'HAM',
            dashed: false,
            lapTimes,
        },
        {
            color: '#5bd8cb',
            label: 'RUS',
            dashed: true,
            lapTimes,
        },
        {
            color: '#418179',
            label: 'ALO',
            dashed: false,
            lapTimes,
        },
        {
            color: '#418179',
            label: 'STR',
            dashed: true,
            lapTimes,
        },
        {
            color: '#568ebb',
            label: 'GAS',
            dashed: false,
            lapTimes,
        },
        {
            color: '#568ebb',
            label: 'OCO',
            dashed: true,
            lapTimes,
        },
        {
            color: '#f8f8f8',
            label: 'MAG',
            dashed: false,
            lapTimes,
        },
        {
            color: '#f8f8f8',
            label: 'HUL',
            dashed: true,
            lapTimes,
        },
        {
            color: '#902b29',
            label: 'BOT',
            dashed: false,
            lapTimes,
        },
        {
            color: '#902b29',
            label: 'ZHO',
            dashed: true,
            lapTimes,
        },
        {
            color: '#edb36d',
            label: 'PIA',
            dashed: false,
            lapTimes,
        },
        {
            color: '#edb36d',
            label: 'NOR',
            dashed: true,
            lapTimes,
        },
        {
            color: '#82a4df',
            label: 'DEV',
            dashed: false,
            lapTimes,
        },
        {
            color: '#82a4df',
            label: 'SAR',
            dashed: true,
            lapTimes,
        },
        {
            color: '#949ba0',
            label: 'TSU',
            dashed: false,
            lapTimes,
        },
        {
            color: '#949ba0',
            label: 'ALB',
            dashed: true,
            lapTimes,
        },
    ],
};

const ChampionshipStandingsPage: React.FunctionComponent<EventGraphsPageProps> = ({
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
                <EventTitle event={season[event]} page={page} />
                <LapByLapGraph data={data} />
            </EventContainer>
        )}
        subHeader={<EventNavbar championship={championship} year={year} event={event} season={season} page={page} />}
    />
);

export const getStaticProps = async ({ params: { championship, year, event } }: EventGraphsPathParams) => {
    const season = await seasonApi(championship, parseInt(year, 10));

    return {
        props: {
            championship,
            year,
            event,
            season,
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
