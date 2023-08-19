'use client';

import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import { FunctionComponent } from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

const ConstructorsAnalyticsTableHead: FunctionComponent = () => (
    <TableHead>
        <TableRow>
            <StyledTableCell align="left">Position</StyledTableCell>
            <StyledTableCell align="left">Constructor</StyledTableCell>
            <StyledTableCell align="right">Points</StyledTableCell>
            <StyledTableCell align="right">Wins</StyledTableCell>
        </TableRow>
    </TableHead>
);

export default ConstructorsAnalyticsTableHead;
