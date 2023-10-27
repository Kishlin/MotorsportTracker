import React, { ReactNode } from 'react';
import LinkUsingNext from 'next/link';

declare type LinkProps = {
    children: ReactNode,
    to: string,
};

const Link: React.FunctionComponent<LinkProps> = ({ to, children }) => (
    <LinkUsingNext href={to} legacyBehavior>
        {children}
    </LinkUsingNext>
);

export default Link;
