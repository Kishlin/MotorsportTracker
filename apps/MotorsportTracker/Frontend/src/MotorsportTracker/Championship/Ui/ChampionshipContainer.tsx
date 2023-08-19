'use client';

import { FunctionComponent, ReactNode } from 'react';
import { Grid } from '@mui/material';

declare type ChampionshipContainerProps = { children: ReactNode };

const ChampionshipContainer: FunctionComponent<ChampionshipContainerProps> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ px: 8, py: 2 }}>
        {children}
    </Grid>
);

export default ChampionshipContainer;
