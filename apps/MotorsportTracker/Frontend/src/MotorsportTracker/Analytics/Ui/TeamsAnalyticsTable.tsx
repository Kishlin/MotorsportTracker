'use client';

import { FunctionComponent } from 'react';
import Box from '@mui/material/Box';

import TeamsAnalyticsTableHead from './Table/TeamsAnalyticsTableHead';
import TeamsAnalyticsRow from './Table/TeamsAnalyticsRow';
import AnalyticsTableBody from './Table/AnalyticsTableBody';
import AnalyticsTable from './Table/AnalyticsTable';
import { TeamAnalytics } from '../Types/Index';

declare type TeamAnalyticsTableProps = {
    analytics: Array<TeamAnalytics>,
};

const TeamsAnalyticsTable: FunctionComponent<TeamAnalyticsTableProps> = ({ analytics }) => {
    if (0 === analytics.length) {
        return <noscript />;
    }

    return (
        <Box>
            <AnalyticsTable>
                <TeamsAnalyticsTableHead />
                <AnalyticsTableBody>
                    {analytics.map(
                        (data: TeamAnalytics) => (
                            <TeamsAnalyticsRow key={data.id} analytics={data} />
                        ),
                    )}
                </AnalyticsTableBody>
            </AnalyticsTable>
        </Box>
    );
};

export default TeamsAnalyticsTable;
