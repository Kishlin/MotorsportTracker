import Typography from '@mui/material/Typography';
import { Stack } from '@mui/material';
import React from 'react';

import { ConstructorAnalytics, DriverAnalytics, TeamAnalytics } from '../Types/Index';
import ConstructorsAnalyticsTable from './ConstructorsAnalyticsTable';
import DriversAnalyticsTable from './DriversAnalyticsTable';
import TeamsAnalyticsTable from './TeamsAnalyticsTable';

declare type StandingsContentProps = {
    constructorsAnalytics: Array<ConstructorAnalytics>,
    driversAnalytics: Array<DriverAnalytics>,
    teamsAnalytics: Array<TeamAnalytics>,
}

const AnalyticsContent: React.FunctionComponent<StandingsContentProps> = ({
    constructorsAnalytics,
    driversAnalytics,
    teamsAnalytics,
}) => {
    if (0 === constructorsAnalytics.length && 0 === driversAnalytics.length && 0 === teamsAnalytics.length) {
        return <Typography sx={{ mt: 4 }} align="center">No stats available at this time.</Typography>
    }

    return (
        <Stack spacing={4}>
            <DriversAnalyticsTable analytics={driversAnalytics} />
            <TeamsAnalyticsTable analytics={teamsAnalytics} />
            <ConstructorsAnalyticsTable analytics={constructorsAnalytics} />
        </Stack>
    );
};

export default AnalyticsContent;
