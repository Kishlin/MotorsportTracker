import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const Layout: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container sx={{ width: '100%', height: '100%' }}>
        { children }
    </Grid>
);

export default Layout;
