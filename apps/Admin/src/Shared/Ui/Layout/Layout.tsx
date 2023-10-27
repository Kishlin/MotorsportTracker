'use client';

import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';
import Box from '@mui/material/Box';

import LayoutContent from './LayoutContent';
import LayoutHeader from './LayoutHeader';

declare type LayoutProps = {
    children: ReactNode,
};

const Layout: React.FunctionComponent<LayoutProps> = ({ children }) => (
    <Box sx={{ display: 'flex', width: '100%', height: '100%' }}>
        <LayoutHeader />
        <LayoutContent>
            <Grid container spacing={0} direction="column" sx={{ px: 8, my: 2 }}>
                {children}
            </Grid>
        </LayoutContent>
    </Box>
);

export default Layout;
