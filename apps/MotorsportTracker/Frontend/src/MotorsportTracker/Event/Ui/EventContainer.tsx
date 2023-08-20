'use client';

import { FunctionComponent, ReactNode } from 'react';
import Grid from '@mui/material/Grid';

const EventContainer: FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 8, py: 2 }}>
        {children}
    </Grid>
);

export default EventContainer;
