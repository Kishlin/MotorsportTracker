'use client';

import { FunctionComponent, ReactNode } from 'react';
import TableBody from '@mui/material/TableBody';

declare type SessionTableBodyProps = {
    children: ReactNode,
};

const SessionTableBody: FunctionComponent<SessionTableBodyProps> = ({ children }) => (
    <TableBody>
        {children}
    </TableBody>
);

export default SessionTableBody;
