'use client';

import React, { FunctionComponent } from 'react';
import Box from '@mui/material/Box';

import DriversAnalyticsTableHead from './Table/DriversAnalyticsTableHead';
import DriversAnalyticsRow from './Table/DriversAnalyticsRow';
import AnalyticsTableBody from './Table/AnalyticsTableBody';
import AnalyticsTable from './Table/AnalyticsTable';
import { DriverAnalytics } from '../Types/Index';

declare type DriverAnalyticsTableProps = {
    analytics: Array<DriverAnalytics>,
};

const DriversAnalyticsTable: FunctionComponent<DriverAnalyticsTableProps> = ({ analytics }) => {
    if (0 === analytics.length) {
        return <noscript />;
    }

    return (
        <Box>
            <AnalyticsTable>
                <DriversAnalyticsTableHead />
                <AnalyticsTableBody>
                    {analytics.map(
                        (data: DriverAnalytics) => (
                            <DriversAnalyticsRow key={data.id} analytics={data} />
                        ),
                    )}
                </AnalyticsTableBody>
            </AnalyticsTable>
        </Box>
    );
};

export default DriversAnalyticsTable;
