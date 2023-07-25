import TableRow from '@mui/material/TableRow';
import { TableHead } from '@mui/material';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

const SessionTableHead: React.FunctionComponent = () => (
    <TableHead>
        <TableRow>
            <StyledTableCell>Pos</StyledTableCell>
            <StyledTableCell>N°</StyledTableCell>
            <StyledTableCell>Driver</StyledTableCell>
            <StyledTableCell>Team</StyledTableCell>
            <StyledTableCell align="right">Laps</StyledTableCell>
            <StyledTableCell align="right">Time</StyledTableCell>
            <StyledTableCell align="right">Gap</StyledTableCell>
            <StyledTableCell align="right">Interval</StyledTableCell>
            <StyledTableCell align="right">Best</StyledTableCell>
        </TableRow>
    </TableHead>
);

export default SessionTableHead;
