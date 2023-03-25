import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const ChampionshipContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container spacing={0} direction="column">
        {children}
    </Grid>
);

export default ChampionshipContainer;
