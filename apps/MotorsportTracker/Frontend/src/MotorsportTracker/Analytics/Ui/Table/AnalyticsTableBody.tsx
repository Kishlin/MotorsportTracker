'use client';

import TableBody from '@mui/material/TableBody';
import { FunctionComponent, ReactNode } from 'react';

declare type SessionTableBodyProps = {
    children: ReactNode,
};

const AnalyticsTableBody: FunctionComponent<SessionTableBodyProps> = ({ children }) => (
    <TableBody>
        {children}
    </TableBody>
);

export default AnalyticsTableBody;
