// @ts-ignore
import React from 'react';
import dynamic from 'next/dynamic';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';
import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';

const Chart = dynamic(() => import('react-apexcharts'), { ssr: false });

declare type StandingsPageProps = {
    events: string[],
    drivers: { name: string, color: string, data: number[] }[],
    teams: { name: string, color: string, data: number[] }[],
};

const StandingsPage: React.FunctionComponent<StandingsPageProps> = ({ events, drivers, teams }) => {
    const renderTooltip = ({ w }) => {
        let items = '';
        w.globals.tooltip.ttItems.forEach((x) => {
            items += x.outerHTML;
        });

        return items;
    };

    const changeLabelsOnChartDrawn = () => {
        const xAxisCollection: HTMLCollection = document.getElementsByClassName('apexcharts-xaxis-texts-g');
        if (1 !== xAxisCollection.length) {
            return;
        }
        const newXAxis: Node[] = [];

        for (let i = 0; i < xAxisCollection[0].children.length; i += 1) {
            const text = xAxisCollection[0].children[i];
            const country = text.children[1].textContent;

            const x: number = text.getAttribute('x') as unknown as number;
            const y: number = text.getAttribute('y') as unknown as number;

            const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
            image.setAttributeNS(null, 'x', `${x - 12}`);
            image.setAttributeNS(null, 'y', `${y - 12}`);
            image.setAttributeNS(null, 'width', '24');
            image.setAttributeNS(null, 'height', '18');
            image.setAttributeNS(null, 'href', `/assets/flags/4x3/${country}.svg`);
            image.setAttribute('style', 'transition: .15s ease all;');
            image.setAttribute('class', 'country-flag');

            newXAxis.push(image);
        }

        xAxisCollection[0].innerHTML = '';
        xAxisCollection[0].append(...newXAxis);
    };

    const data = {
        options: {
            chart: {
                id: 'apexchart-example',
                toolbar: {
                    show: true,
                    tools: {
                        download: false,
                    },
                },
                background: '#121212',
                events: {
                    mounted: changeLabelsOnChartDrawn,
                    updated: changeLabelsOnChartDrawn,
                },
            },
            xaxis: {
                categories: events,
                tooltip: {
                    enabled: false,
                },
            },
            yaxis: {
                title: {
                    text: 'Points',
                },
                tooltip: {
                    enabled: false,
                },
            },
            menu: {
                enabled: false,
            },
            tooltip: {
                theme: 'dark',
                custom: renderTooltip,
            },
            stroke: {
                width: 2,
            },
            theme: {
                mode: 'dark',
            },
        },
        series: teams,
    };

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={('undefined' !== typeof window) && <Chart options={data.options} series={data.series} type="line" width="85%" height={700} />}
        />
    );
};

export async function getServerSideProps({ params: { championship, year } }): Promise<{ props: StandingsPageProps }> {
    const driversResponse = await fetch(`http://backend:8000/api/v1/standings/drivers/${championship}/${year}`);
    const teamsResponse = await fetch(`http://backend:8000/api/v1/standings/teams/${championship}/${year}`);

    const driversData = await driversResponse.json() as { events: string[], standings: { name: string, color: string, data: number[] }[]};
    const teamsData = await teamsResponse.json() as { events: string[], standings: { name: string, color: string, data: number[] }[]};

    return {
        props: {
            events: driversData.events,
            drivers: driversData.standings,
            teams: teamsData.standings,
        },
    };
}

export default StandingsPage;
