import React, { ReactNode } from 'react';
import Grid from '@mui/material/Grid';

declare type NavContainerProps = {
    children: ReactNode,
};

const NavContainer: React.FunctionComponent<NavContainerProps> = ({ children }) => (
    <Grid container direction="row" justifyContent="space-between">
        {children}
    </Grid>
);

export default NavContainer;
