import Grid from '@mui/material/Grid';
import React from 'react';

declare type HistoriesContainerProps = {
    children: React.ReactNode,
};

const HistoriesContainer: React.FunctionComponent<HistoriesContainerProps> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 16, py: 2 }}>
        {children}
    </Grid>
);

export default HistoriesContainer;
