import Grid from '@mui/material/Grid';
import React from 'react';
import Box from '@mui/material/Box';

declare type GraphContainerProps = {
    children: React.ReactNode,
    maxWidth?: number,
};

const GraphContainer: React.FunctionComponent<GraphContainerProps> = ({ children, maxWidth }) => (
    <Grid item>
        <Box sx={{ maxWidth: `${maxWidth}px`, margin: 'auto' }}>
            {children}
        </Box>
    </Grid>
);

GraphContainer.defaultProps = {
    maxWidth: 800,
};

export default GraphContainer;
