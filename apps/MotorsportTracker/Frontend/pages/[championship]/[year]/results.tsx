// @ts-ignore
import React from 'react';

import Layout from '../../../src/Shared/Ui/Layout/Layout';

import ChampionshipContainer from '../../../src/MotorsportTracker/Championship/Ui/ChampionshipContainer';
import ChampionshipNavbar from '../../../src/MotorsportTracker/Championship/Nav/ChampionshipNavbar';
import MotorsportTrackerMenu from '../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import championships from '../../../src/MotorsportTracker/Config/Championships';

declare type ChampionshipResultsPathParams = {
    params: {
        championship: string,
        year: string,
    },
};

declare type ChampionshipResultsPageProps = {
    championship: string,
    year: string,
    page: string,
}

const ChampionshipResultsPage: React.FunctionComponent<ChampionshipResultsPageProps> = ({
    championship,
    year,
    page,
}) => (
    <Layout
        menu={<MotorsportTrackerMenu />}
        content={(
            <ChampionshipContainer>
                <ChampionshipNavbar championship={championship} year={year} page={page} />
                <p style={{ textAlign: 'center' }}>No results available at this time.</p>
            </ChampionshipContainer>
        )}
    />
);

export const getStaticProps = async ({ params: { championship, year } }: ChampionshipResultsPathParams) => (
    { props: { championship, year, page: 'results' } }
);

export async function getStaticPaths(): Promise<{ paths: Array<ChampionshipResultsPathParams>, fallback: boolean }> {
    const paths: Array<ChampionshipResultsPathParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.forEach((year: number) => {
            paths.push({ params: { championship: slug, year: year.toString() } });
        });
    });

    return { paths, fallback: false };
}

export default ChampionshipResultsPage;
