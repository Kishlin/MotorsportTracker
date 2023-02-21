import React from 'react';
import { styled } from '@mui/material/styles';
import MuiAppBar, { AppBarProps as MuiAppBarProps } from '@mui/material/AppBar';
import Toolbar from '@mui/material/Toolbar';
import Typography from '@mui/material/Typography';
import IconButton from '@mui/material/IconButton';
import MenuIcon from '@mui/icons-material/Menu';

interface AppBarProps extends MuiAppBarProps {
    drawerwidth: number;
    open?: boolean;
}

const StyledAppBar = styled(
    MuiAppBar,
    { shouldForwardProp: (prop) => 'open' !== prop && 'drawerwidth' !== prop },
)<AppBarProps>(
    ({ theme, open, drawerwidth }) => ({
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
        ...(open && {
            width: `calc(100% - ${drawerwidth}px)`,
            transition: theme.transitions.create(['margin', 'width'], {
                easing: theme.transitions.easing.easeOut,
                duration: theme.transitions.duration.enteringScreen,
            }),
            marginRight: drawerwidth,
        }),
    }),
);

declare type LayoutHeaderPropsType = {
    drawerWidth: number,
    handleDrawerOpen: () => void,
    open: boolean,
}

const Layout: React.FunctionComponent<LayoutHeaderPropsType> = ({ drawerWidth, handleDrawerOpen, open }) => (
    <StyledAppBar drawerwidth={drawerWidth} open={open} position="fixed" sx={{ backgroundColor: 'background.paper' }}>
        <Toolbar>
            <Typography variant="h6" noWrap sx={{ flexGrow: 1 }} component="div">
                Motorsport Tracker
            </Typography>
            <IconButton
                sx={open ? { display: 'none' } : {}}
                onClick={handleDrawerOpen}
                aria-label="open menu"
                color="inherit"
                edge="end"
            >
                <MenuIcon />
            </IconButton>
        </Toolbar>
    </StyledAppBar>
);

export default Layout;
