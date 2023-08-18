// noinspection HtmlRequiredTitleElement

import { ReactNode } from 'react';

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
            {children}
        </body>
    </html>
);

export default RootLayout;
