import Grid from '@mui/material/Grid';
import React from 'react';

declare type GraphContainerProps = {
    children: React.ReactNode,
};

const GraphContainer: React.FunctionComponent<GraphContainerProps> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 16, py: 2 }}>
        {children}
    </Grid>
);

export default GraphContainer;
