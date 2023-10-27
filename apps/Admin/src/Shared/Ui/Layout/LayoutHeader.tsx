import MuiAppBar, { AppBarProps as MuiAppBarProps } from '@mui/material/AppBar';
import Typography from '@mui/material/Typography';
import { styled } from '@mui/material/styles';
import Toolbar from '@mui/material/Toolbar';
import React from 'react';

const StyledAppBar = styled(
    MuiAppBar,
    { shouldForwardProp: (prop) => 'open' !== prop && 'drawerwidth' !== prop },
)<MuiAppBarProps>(
    () => ({
        backgroundImage: 'linear-gradient(rgba(55, 55, 55, 1), rgba(55, 55, 55, 1))',
    }),
);

const LayoutHeader: React.FunctionComponent = () => (
    <StyledAppBar position="fixed" elevation={0}>
        <Toolbar>
            <Typography variant="h6" noWrap sx={{ flexGrow: 1 }} component="div">
                Motorsport Tracker - Admin
            </Typography>
        </Toolbar>
    </StyledAppBar>
);

export default LayoutHeader;
