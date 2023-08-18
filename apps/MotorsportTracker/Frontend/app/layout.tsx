import { ReactNode } from 'react';
import { Metadata } from 'next';

import RootLayout from './RootLayout';
import MuiLayout from './MuiLayout';

// noinspection JSUnusedGlobalSymbols
export const metadata: Metadata = {
    title: 'Motorsport Tracker',
    description: 'Stats and Data for motorsport series.',
};

const Layout = ({
    children,
}: {
    children: ReactNode
}) => (
    <RootLayout>
        <MuiLayout>
            {children}
        </MuiLayout>
    </RootLayout>
);

export default Layout;
