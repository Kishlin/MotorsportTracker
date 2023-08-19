'use client';

import { FunctionComponent } from 'react';
import Box from '@mui/material/Box';

import ConstructorsAnalyticsTableHead from './Table/ConstructorsAnalyticsTableHead';
import ConstructorsAnalyticsRow from './Table/ConstructorsAnalyticsRow';
import AnalyticsTableBody from './Table/AnalyticsTableBody';
import { ConstructorAnalytics } from '../Types/Index';
import AnalyticsTable from './Table/AnalyticsTable';

declare type ConstructorAnalyticsTableProps = {
    analytics: Array<ConstructorAnalytics>,
};

const ConstructorsAnalyticsTable: FunctionComponent<ConstructorAnalyticsTableProps> = ({ analytics }) => {
    if (0 === analytics.length) {
        return <noscript />;
    }

    return (
        <Box>
            <AnalyticsTable>
                <ConstructorsAnalyticsTableHead />
                <AnalyticsTableBody>
                    {analytics.map(
                        (data: ConstructorAnalytics) => (
                            <ConstructorsAnalyticsRow key={data.id} analytics={data} />
                        ),
                    )}
                </AnalyticsTableBody>
            </AnalyticsTable>
        </Box>
    );
};

export default ConstructorsAnalyticsTable;
