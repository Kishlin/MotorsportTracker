// noinspection JSUnusedGlobalSymbols

import { notFound } from 'next/navigation';
import { headers } from 'next/headers';
import { ReactNode } from 'react';
import { Metadata } from 'next';

import MotorsportTrackerMenu from '../../../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import championships from '../../../../../../src/MotorsportTracker/Config/Championships';
import EventNavbar from '../../../../../../src/MotorsportTracker/Event/Nav/EventNavbar';
import seasonApi from '../../../../../../src/MotorsportTracker/Event/Api/SeasonApi';
import { SeasonEvents } from '../../../../../../src/MotorsportTracker/Shared/Types';
import MotorsportTrackerLayout from '../../../../../../src/Shared/Ui/Layout/Layout';

declare type PageParams = {
    championship: string,
    year: string,
    event: string,
};

export async function generateMetadata(): Promise<Metadata> {
    const pathname = headers().get('x-pathname');

    if (null === pathname) {
        return {};
    }

    const [, championship, year, event, page] = pathname.slice(1).split('/');

    const pageUcFirst = page.slice(0, 1).toUpperCase() + page.slice(1);

    return {
        title: `${event}, ${championships[championship].shortName} ${year} ${pageUcFirst} - Motorsport Tracker`,
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

    const [, championship, year, event] = pathname.slice(1).split('/');

    const season = await seasonApi(championship, parseInt(year, 10));

    if (undefined === season[event]) {
        return notFound();
    }

    return (
        <MotorsportTrackerLayout
            menu={<MotorsportTrackerMenu />}
            subHeader={(<EventNavbar season={season} />)}
        >
            {children}
        </MotorsportTrackerLayout>
    );
};

export async function generateStaticParams():Promise<Array<PageParams>> {
    const paramsPromise: Array<Promise<void|PageParams[]>> = [];
    const paths: Array<PageParams> = [];

    Object.keys(championships).forEach((slug: string) => {
        championships[slug].years.forEach(async (year: number) => {
            if (2015 <= year) {
                paramsPromise.push(
                    seasonApi(slug, year)
                        .then((result: SeasonEvents) => Object.entries(result).map(([, seasonEvent]) => ({
                            championship: slug,
                            year: year.toString(),
                            event: seasonEvent.slug,
                        }))),
                );
            }
        });
    });

    (await Promise.all(paramsPromise)).forEach((paramsList: PageParams[]) => {
        paths.push(...paramsList);
    });

    return paths;
}

export const dynamicParams = true;

export default Layout;
