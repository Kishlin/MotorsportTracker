import React, { ReactNode } from 'react';
import Box from '@mui/material/Box';

import LayoutMenu from './LayoutMenu';
import LayoutContent from './LayoutContent';
import LayoutHeader from './LayoutHeader';

const drawerWidth = 240;

declare type LayoutProps = {
    content: ReactNode,
    menu: ReactNode,
    subHeader?: ReactNode,
};

const Layout: React.FunctionComponent<LayoutProps> = ({ menu, content, subHeader }) => {
    const [open, setOpen] = React.useState(true);

    const handleDrawerOpen = () => {
        setOpen(true);
    };

    const handleDrawerClose = () => {
        setOpen(false);
    };

    return (
        <Box sx={{ display: 'flex', width: '100%', height: '100%' }}>
            <LayoutHeader
                handleDrawerOpen={handleDrawerOpen}
                drawerWidth={drawerWidth}
                open={open}
            />
            <LayoutContent
                subHeader={subHeader}
                drawerWidth={drawerWidth}
                content={content}
                open={open}
            />
            <LayoutMenu menu={menu} drawerWidth={drawerWidth} handleDrawerClose={handleDrawerClose} open={open} />
        </Box>
    );
};

Layout.defaultProps = {
    subHeader: undefined,
};

export default Layout;
