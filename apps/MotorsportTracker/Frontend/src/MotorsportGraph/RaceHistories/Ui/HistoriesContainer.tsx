'use client';

import { FunctionComponent, ReactNode } from 'react';
import Grid from '@mui/material/Grid';

declare type HistoriesContainerProps = {
    children: ReactNode,
};

const HistoriesContainer: FunctionComponent<HistoriesContainerProps> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 16, py: 2 }}>
        {children}
    </Grid>
);

export default HistoriesContainer;
