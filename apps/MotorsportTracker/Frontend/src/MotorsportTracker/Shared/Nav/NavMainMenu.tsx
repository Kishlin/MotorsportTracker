import Toolbar from '@mui/material/Toolbar';
import Grid from '@mui/material/Grid';
import React from 'react';

declare type NavMainMenuProps = {
    children: React.ReactNode,
};

const NavMainMenu: React.FunctionComponent<NavMainMenuProps> = ({ children }) => (
    <Grid item flexGrow={99} sx={{ backgroundColor: '#494949' }}>
        <Toolbar disableGutters>
            {children}
        </Toolbar>
    </Grid>
);

export default NavMainMenu;
