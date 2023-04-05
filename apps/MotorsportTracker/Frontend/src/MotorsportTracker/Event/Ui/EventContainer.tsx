import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const EventContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 8, py: 2 }}>
        {children}
    </Grid>
);

export default EventContainer;
