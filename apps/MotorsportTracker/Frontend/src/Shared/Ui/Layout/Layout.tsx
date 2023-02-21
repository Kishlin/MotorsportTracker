import React, { ReactNode } from 'react';
import Box from '@mui/material/Box';

import LayoutMenu from './LayoutMenu';
import LayoutContent from './LayoutContent';
import LayoutHeader from './LayoutHeader';

const drawerWidth = 240;

const Layout: React.FunctionComponent<{ menu: ReactNode, content: ReactNode }> = ({ menu, content}) => {
    const [open, setOpen] = React.useState(false);

    const handleDrawerOpen = () => {
        setOpen(true);
    };

    const handleDrawerClose = () => {
        setOpen(false);
    };

    return (
        <Box sx={{ display: 'flex', width: '100%', height: '100%' }}>
            <LayoutHeader drawerWidth={drawerWidth} handleDrawerOpen={handleDrawerOpen} open={open} />
            <LayoutContent content={content} drawerWidth={drawerWidth} open={open} />
            <LayoutMenu menu={menu} drawerWidth={drawerWidth} handleDrawerClose={handleDrawerClose} open={open} />
        </Box>
    );
};

export default Layout;
