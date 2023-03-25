import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const ScheduleContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 8, my: 2 }}>
        {children}
    </Grid>
);

export default ScheduleContainer;
