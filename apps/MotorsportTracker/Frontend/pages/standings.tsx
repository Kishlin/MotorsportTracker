// @ts-ignore
import React from 'react';
import dynamic from 'next/dynamic';
import Layout from '../src/Shared/Ui/Layout/Layout';
import MotorsportTrackerMenu from '../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';

const Chart = dynamic(() => import('react-apexcharts'), { ssr: false });

const StandingsPage: React.FunctionComponent = () => {
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
                categories: ['bh', 'sa', 'au', 'it', 'us', 'es', 'mc', 'az', 'ca', 'gb', 'at', 'fr', 'hu', 'be', 'nl', 'it', 'sg', 'jp', 'us', 'mx', 'br', 'ae'],
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
        series: [
            {
                name: 'Red Bull Racing',
                color: '#0022ff',
                data: [0, 37, 55, 113, 141, 185, 225, 269, 294, 318, 349, 386, 421, 465, 501, 535, 566, 609, 656, 696, 719, 759],
            },
            {
                name: 'Ferrari',
                color: '#ff0000',
                data: [44, 78, 104, 124, 157, 169, 199, 199, 228, 265, 303, 314, 324, 347, 366, 399, 432, 447, 462, 487, 524, 554],
            },
            {
                name: 'Mercedes',
                color: '#a8a8a8',
                data: [27, 38, 55, 67, 85, 110, 124, 151, 178, 194, 227, 260, 294, 306, 336, 361, 363, 377, 406, 447, 505, 515],
            },
            {
                name: 'Alpine',
                color: '#03f4ff',
                data: [8, 16, 22, 22, 26, 34, 40, 47, 57, 67, 81, 93, 99, 115, 125, 125, 125, 173, 191, 197, 201, 215],
            },
            {
                name: 'McLaren',
                color: '#ff7400',
                data: [0, 6, 24, 66, 66, 70, 79, 85, 85, 93, 101, 109, 115, 115, 121, 127, 149, 150, 158, 166, 168, 179],
            },
            {
                name: 'Alfa Romeo',
                color: '#7a1423',
                data: [9, 9, 13, 25, 31, 39, 41, 41, 51, 51, 51, 51, 51, 51, 51, 52, 52, 52, 52, 53, 55, 55],
            },
            {
                name: 'Aston martin Racing',
                color: '#208624',
                data: [0, 0, 0, 5, 6, 6, 7, 15, 16, 18, 18, 19, 20, 24, 25, 25, 37, 45, 49, 49, 50, 55],
            },
            {
                name: 'Haas F1 Team',
                color: '#b5b8cb',
                data: [10, 12, 12, 15, 15, 15, 15, 15, 15, 20, 34, 34, 34, 34, 34, 34, 34, 34, 36, 36, 37, 37],
            },
            {
                name: 'Alpha Tauri',
                color: '#3b53ff',
                data: [4, 8, 10, 16, 16, 17, 17, 27, 27, 27, 27, 27, 27, 29, 29, 33, 34, 34, 35, 35, 35, 35],
            },
            {
                name: 'Williams',
                color: '#e2fdff',
                data: [0, 0, 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 5, 5, 7, 7, 7, 7, 7],
            },
        ],
    };

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={('undefined' !== typeof window) && <Chart options={data.options} series={data.series} type="line" width="85%" height={1000} />}
        />
    );
};

export default StandingsPage;
