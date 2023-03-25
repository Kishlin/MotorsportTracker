import React, { ReactNode } from 'react';
import { styled } from '@mui/material/styles';

declare type MainProps = {
    drawerwidth: number;
    open?: boolean;
}

const Main = styled(
    'main',
    { shouldForwardProp: (prop) => 'open' !== prop && 'drawerwidth' !== prop },
)<MainProps>(
    ({ theme, open, drawerwidth }) => ({
        flexGrow: 1,
        transition: theme.transitions.create('margin', {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
        marginRight: -drawerwidth,
        ...(open && {
            transition: theme.transitions.create('margin', {
                easing: theme.transitions.easing.easeOut,
                duration: theme.transitions.duration.enteringScreen,
            }),
            marginRight: 0,
        }),
    }),
);

const DrawerHeader = styled('div')(({ theme }) => ({
    display: 'flex',
    alignItems: 'center',
    padding: theme.spacing(0, 1),
    // necessary for content to be below app bar
    ...theme.mixins.toolbar,
    justifyContent: 'flex-start',
}));

declare type LayoutContentProps = {
    hasSubHeader: boolean,
    drawerWidth: number,
    content: ReactNode,
    open: boolean,
}

const LayoutContent: React.FunctionComponent<LayoutContentProps> = ({
    hasSubHeader,
    drawerWidth,
    content,
    open,
}) => (
    <Main open={open} drawerwidth={drawerWidth}>
        <DrawerHeader />
        {hasSubHeader ? <DrawerHeader /> : <noscript />}
        { content }
    </Main>
);

export default LayoutContent;
