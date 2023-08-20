// noinspection JSUnusedGlobalSymbols

import { notFound } from 'next/navigation';
import React, { ReactNode } from 'react';
import { headers } from 'next/headers';
import { Metadata } from 'next';

import availableStandingsApi from '../../../src/MotorsportTracker/Standing/Api/AvailableStandingsApi';
import ChampionshipNavbar from '../../../src/MotorsportTracker/Championship/Nav/ChampionshipNavbar';
import MotorsportTrackerMenu from '../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import championships from '../../../src/MotorsportTracker/Config/Championships';
import MotorsportTrackerLayout from '../../../src/Shared/Ui/Layout/Layout';
import { StandingType } from '../../../src/MotorsportTracker/Shared/Types';

declare type PageParams = {
    championship: string,
    year: string,
};

export async function generateMetadata(): Promise<Metadata> {
    const pathname = headers().get('x-pathname');

    if (null === pathname) {
        return {};
    }

    const [, championship, year, page] = pathname.slice(1).split('/');

    const pageUcFirst = page.slice(0, 1).toUpperCase() + page.slice(1);

    return {
        title: `${championships[championship].shortName} ${year} ${pageUcFirst} - Motorsport Tracker`,
    };
}

const Layout = async ({
    children,
}: {
    children: ReactNode
}) => {
    const pathname = headers().get('x-pathname');

    if (null === pathname) {
        return children;
    }

    const [, championship, year, page] = pathname.slice(1).split('/');

    const availableStandings = await availableStandingsApi(championship, year);

    // eslint-disable-next-line
    ['constructor', 'team', 'driver'].forEach((type: StandingType) => {
        if (page === `standings-${type}` && false === availableStandings[type]) {
            return notFound();
        }
    });

    return (
        <MotorsportTrackerLayout
            menu={<MotorsportTrackerMenu />}
            subHeader={(<ChampionshipNavbar availableStandings={availableStandings} />)}
        >
            {children}
        </MotorsportTrackerLayout>
    );
};

export function generateStaticParams(): Array<PageParams> {
    const paths: Array<PageParams> = [];

    Object.entries(championships).forEach(([slug, championship]) => {
        championship.years.forEach((year: number) => {
            paths.push({ championship: slug, year: year.toString() });
        });
    });

    return paths;
}

export const dynamicParams = true;

export default Layout;
