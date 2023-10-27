import { styled } from '@mui/material/styles';
import React, { ReactNode } from 'react';

const Main = styled(
    'main',
)(
    () => ({
        flexGrow: 1,
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
    children: ReactNode,
}

const LayoutContent: React.FunctionComponent<LayoutContentProps> = ({ children }) => (
    <Main>
        <DrawerHeader />
        {children}
    </Main>
);

export default LayoutContent;
