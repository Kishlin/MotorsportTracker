'use client';

import { FunctionComponent, ReactNode } from 'react';
import Grid from '@mui/material/Grid';

declare type GraphContainerProps = {
    children: ReactNode,
};

const GraphContainer: FunctionComponent<GraphContainerProps> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 16, py: 2 }}>
        {children}
    </Grid>
);

export default GraphContainer;
