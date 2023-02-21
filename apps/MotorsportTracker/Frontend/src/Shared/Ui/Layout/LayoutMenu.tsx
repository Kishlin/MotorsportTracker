import React, { ReactNode } from 'react';
import { styled } from '@mui/material/styles';
import Drawer from '@mui/material/Drawer';
import Divider from '@mui/material/Divider';
import IconButton from '@mui/material/IconButton';
import ChevronRightIcon from '@mui/icons-material/ChevronRight';

const DrawerHeader = styled('div')(({ theme }) => ({
    display: 'flex',
    alignItems: 'center',
    padding: theme.spacing(0, 1),
    // necessary for content to be below app bar
    ...theme.mixins.toolbar,
    justifyContent: 'flex-start',
}));

declare type LayoutMenuProps = {
    menu: ReactNode,
    drawerWidth: number,
    handleDrawerClose: () => void,
    open: boolean,
};

const LayoutMenu: React.FunctionComponent<LayoutMenuProps> = ({
    menu,
    drawerWidth,
    open,
    handleDrawerClose,
}) => (
    <Drawer
        sx={{
            width: drawerWidth,
            flexShrink: 0,
            '& .MuiDrawer-paper': {
                width: drawerWidth,
            },
        }}
        variant="persistent"
        anchor="right"
        open={open}
    >
        <DrawerHeader onClick={handleDrawerClose} sx={{ cursor: 'pointer' }}>
            <IconButton>
                <ChevronRightIcon />
            </IconButton>
        </DrawerHeader>
        <Divider />
        { menu }
    </Drawer>
);

export default LayoutMenu;
