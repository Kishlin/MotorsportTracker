// @ts-ignore
import React from 'react';

import Layout from '../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import championships from '../../../src/MotorsportTracker/Config/Championships';
import ChampionshipContainer from '../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ChampionshipNavbar from '../../../src/MotorsportTracker/Championship/Nav/ChampionshipNavbar';

declare type ChampionshipStandingsPathParams = {
    params: {
        championship: string,
        year: string,
    },
};

declare type ChampionshipStandingsPageProps = {
    championship: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<ChampionshipStandingsPageProps> = ({
    championship,
    year,
    page,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ChampionshipContainer>
                <ChampionshipNavbar championship={championship} year={year} page={page} />
                <p style={{ textAlign: 'center' }}>No standings available at this time.</p>
            </ChampionshipContainer>
        )}
    />
);

export const getStaticProps = async ({ params: { championship, year } }: ChampionshipStandingsPathParams) => (
    { props: { championship, year, page: 'results' } }
);

export async function getStaticPaths(): Promise<{ paths: Array<ChampionshipStandingsPathParams>, fallback: boolean }> {
    const paths: Array<ChampionshipStandingsPathParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.forEach((year: number) => {
            paths.push({ params: { championship: slug, year: year.toString() } });
        });
    });

    return { paths, fallback: false };
}

export default ChampionshipStandingsPage;
