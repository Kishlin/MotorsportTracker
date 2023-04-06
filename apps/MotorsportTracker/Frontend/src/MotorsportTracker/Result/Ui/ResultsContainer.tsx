import Grid from '@mui/material/Grid';
import React from 'react';

declare type ResultsContainerProps = {
    children: React.ReactNode,
};

const ResultsContainer: React.FunctionComponent<ResultsContainerProps> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 8, py: 2 }}>
        {children}
    </Grid>
);

export default ResultsContainer;
