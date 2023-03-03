// @ts-ignore
import React from 'react';
import dynamic from 'next/dynamic';
import Box from '@mui/material/Box';
import {
    Grid,
    Tab,
    Tabs,
    Typography,
} from '@mui/material';

import Layout from '../../../../src/Shared/Ui/Layout/Layout';
import MotorsportTrackerMenu from '../../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';

const Chart = dynamic(() => import('react-apexcharts'), { ssr: false });

declare type Standing = { name: string, color: string, data: number[] };

declare type StandingsAPIType = { events: string[], standings: Standing[] };

declare type StandingsPageProps = {
    events: string[],
    drivers: Standing[],
    teams: Standing[],
};

declare type StandingsPathParams = {
    params: {
        championship: string,
        year: string,
    }
};

interface TabPanelProps {
    children: React.ReactNode;
    index: number;
    value: number;
}

const TabPanel = (props: TabPanelProps) => {
    const {
        children,
        value,
        index,
        ...other
    } = props;

    return (
        <div
            role="tabpanel"
            hidden={value !== index}
            id={`simple-tabpanel-${index}`}
            aria-labelledby={`simple-tab-${index}`}
            {...other}
        >
            {value === index && (
                <Box sx={{ p: 3 }}>
                    <Typography>{children}</Typography>
                </Box>
            )}
        </div>
    );
};

function a11yProps(index: number) {
    return {
        id: `simple-tab-${index}`,
        'aria-controls': `simple-tabpanel-${index}`,
    };
}

const StandingsPage: React.FunctionComponent<StandingsPageProps> = ({ events, drivers, teams }) => {
    const [tabIndex, setTabIndex] = React.useState<number>(0);

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

    const chartOptions = {
        chart: {
            id: 'apexchart-example',
            toolbar: {
                show: true,
                tools: {
                    download: false,
                    pan: false,
                },
            },
            background: '#121212',
            events: {
                mounted: changeLabelsOnChartDrawn,
                updated: changeLabelsOnChartDrawn,
            },
            animations: {
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 50,
                },
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
    };

    const handleChange = (event: React.SyntheticEvent, newValue: number) => {
        setTabIndex(newValue);
    };

    return (
        <Layout
            menu={<MotorsportTrackerMenu />}
            content={(
                <Grid container spacing={0} direction="column" sx={{ mx: 8, my: 2, maxWidth: '88%' }}>
                    <Grid item container flexDirection="column" columns={{ xs: 1 }} sx={{ overflow: 'hidden' }}>
                        <Grid item sx={{ borderBottom: 1, borderColor: 'divider' }}>
                            <Tabs value={tabIndex} onChange={handleChange} aria-label="basic tabs example">
                                <Tab label="Drivers" {...a11yProps(0)} />
                                <Tab label="Teams" {...a11yProps(1)} />
                            </Tabs>
                        </Grid>
                        <TabPanel value={tabIndex} index={0}>
                            {('undefined' !== typeof window) && <Chart options={chartOptions} series={drivers} type="line" width="98%" height={650} />}
                        </TabPanel>
                        <TabPanel value={tabIndex} index={1}>
                            {('undefined' !== typeof window) && <Chart options={chartOptions} series={teams} type="line" width="98%" height={650} />}
                        </TabPanel>
                    </Grid>
                </Grid>
            )}
        />
    );
};

export async function getStaticProps({ params: { championship, year } }): Promise<{ props: StandingsPageProps }> {
    const driversResponse = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/drivers/${championship}/${year}`);
    const teamsResponse = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/standings/teams/${championship}/${year}`);

    const driversData = await driversResponse.json() as StandingsAPIType;
    const teamsData = await teamsResponse.json() as StandingsAPIType;

    return {
        props: {
            events: driversData.events,
            drivers: driversData.standings,
            teams: teamsData.standings,
        },
    };
}

export async function getStaticPaths(): Promise<{ paths: Array<StandingsPathParams>, fallback: boolean }> {
    const paths: Array<StandingsPathParams> = [
        { params: { championship: 'formula1', year: '2022' } },
    ];

    return { paths, fallback: false };
}

export default StandingsPage;
