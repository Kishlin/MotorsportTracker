import TableRow from '@mui/material/TableRow';
import { TableHead } from '@mui/material';
import React from 'react';

import SessionTableCell from './SessionTableCell';

const SessionTableHead: React.FunctionComponent = () => (
    <TableHead>
        <TableRow>
            <SessionTableCell>Pos</SessionTableCell>
            <SessionTableCell>N°</SessionTableCell>
            <SessionTableCell>Driver</SessionTableCell>
            <SessionTableCell>Team</SessionTableCell>
            <SessionTableCell align="right">Laps</SessionTableCell>
            <SessionTableCell align="right">Time</SessionTableCell>
            <SessionTableCell align="right">Gap</SessionTableCell>
            <SessionTableCell align="right">Interval</SessionTableCell>
            <SessionTableCell align="right">Best</SessionTableCell>
        </TableRow>
    </TableHead>
);

export default SessionTableHead;
