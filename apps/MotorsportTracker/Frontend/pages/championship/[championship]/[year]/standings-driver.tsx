// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import championships from '../../../../src/MotorsportTracker/Config/Championships';
import ChampionshipContainer from '../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ChampionshipNavbar from '../../../../src/MotorsportTracker/Championship/Nav/ChampionshipNavbar';

declare type ChampionshipDriverStandingsPathParams = {
    params: {
        championship: string,
        year: string,
    },
};

declare type ChampionshipDriverStandingsPageProps = {
    championship: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<ChampionshipDriverStandingsPageProps> = ({
    championship,
    year,
    page,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ChampionshipContainer>
                <p style={{ textAlign: 'center' }}>No Driver Standings available at this time.</p>
            </ChampionshipContainer>
        )}
        subHeader={<ChampionshipNavbar championship={championship} year={year} page={page} />}
    />
);

export const getStaticProps = async ({ params: { championship, year } }: ChampionshipDriverStandingsPathParams) => (
    { props: { championship, year, page: 'results' } }
);

export async function getStaticPaths(): Promise<{
    paths: Array<ChampionshipDriverStandingsPathParams>,
    fallback: boolean,
}> {
    const paths: Array<ChampionshipDriverStandingsPathParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.forEach((year: number) => {
            paths.push({ params: { championship: slug, year: year.toString() } });
        });
    });

    return { paths, fallback: false };
}

export default ChampionshipStandingsPage;
