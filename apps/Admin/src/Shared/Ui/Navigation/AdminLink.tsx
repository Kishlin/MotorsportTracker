import React, { ReactNode } from 'react';
import LinkUsingNext from 'next/link';

declare type LinkProps = {
    children: ReactNode,
    to: string,
};

const AdminLink: React.FunctionComponent<LinkProps> = ({ to, children }) => (
    <LinkUsingNext href={to} legacyBehavior>
        {children}
    </LinkUsingNext>
);

export default AdminLink;
