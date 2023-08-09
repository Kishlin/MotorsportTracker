import React from 'react';
import TableBody from '@mui/material/TableBody';

declare type SessionTableBodyProps = {
    children: React.ReactNode,
};

const AnalyticsTableBody: React.FunctionComponent<SessionTableBodyProps> = ({ children }) => (
    <TableBody>
        {children}
    </TableBody>
);

export default AnalyticsTableBody;
