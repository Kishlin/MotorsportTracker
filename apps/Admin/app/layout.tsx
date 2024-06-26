// noinspection HtmlRequiredTitleElement

import { ReactNode } from 'react';
import { Metadata } from 'next';

import ThemeRegistry from './ThemeRegistry';
import { SnackbarProvider } from '../src/Shared/Context/Snackbar/SnackbarContext';

// noinspection JSUnusedGlobalSymbols
export const metadata: Metadata = {
    title: 'Motorsport Tracker - Admin',
    description: 'Stats and Data for motorsport series.',
};

const RootLayout = ({
    children,
}: {
    children: ReactNode
}) => (
    <html lang="en">
        <head>
            <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
            />

            <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/icon?family=Material+Icons"
            />
        </head>
        <body>
            <ThemeRegistry options={{ key: 'mui' }}>
                <SnackbarProvider>
                    {children}
                </SnackbarProvider>
            </ThemeRegistry>
        </body>
    </html>
);

export default RootLayout;
