import SearchIcon from '@mui/icons-material/Search';
import Grid from '@mui/material/Grid';
import Icon from '@mui/material/Icon';
import React from 'react';

declare type NavSearchBarProps = {
    children: React.ReactNode,
};

const NavSearchBar: React.FunctionComponent<NavSearchBarProps> = ({ children }) => (
    <Grid item flexGrow={1} sx={{ backgroundColor: '#5a5a5a', p: 1 }}>
        <Grid container direction="row" justifyContent="space-between">
            <Icon fontSize="large" color="inherit" sx={{ mr: 2 }}>
                <SearchIcon />
            </Icon>
            {children}
        </Grid>
    </Grid>
);

export default NavSearchBar;
