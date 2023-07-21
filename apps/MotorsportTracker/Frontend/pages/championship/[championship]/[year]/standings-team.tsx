// @ts-ignore
import React from 'react';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';

import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import championships from '../../../../src/MotorsportTracker/Config/Championships';
import ChampionshipContainer from '../../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ChampionshipNavbar from '../../../../src/MotorsportTracker/Championship/Nav/ChampionshipNavbar';

declare type ChampionshipTeamStandingsPathParams = {
    params: {
        championship: string,
        year: string,
    },
};

declare type ChampionshipTeamStandingsPageProps = {
    championship: string,
    year: string,
    page: string,
}

const ChampionshipStandingsPage: React.FunctionComponent<ChampionshipTeamStandingsPageProps> = ({
    championship,
    year,
    page,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ChampionshipContainer>
                <p style={{ textAlign: 'center' }}>No Team Standings available at this time.</p>
            </ChampionshipContainer>
        )}
        subHeader={<ChampionshipNavbar championship={championship} year={year} page={page} />}
    />
);

export const getStaticProps = async ({ params: { championship, year } }: ChampionshipTeamStandingsPathParams) => (
    { props: { championship, year, page: 'results' } }
);

export async function getStaticPaths(): Promise<{
    paths: Array<ChampionshipTeamStandingsPathParams>,
    fallback: boolean,
}> {
    const paths: Array<ChampionshipTeamStandingsPathParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.forEach((year: number) => {
            paths.push({ params: { championship: slug, year: year.toString() } });
        });
    });

    return { paths, fallback: false };
}

export default ChampionshipStandingsPage;
