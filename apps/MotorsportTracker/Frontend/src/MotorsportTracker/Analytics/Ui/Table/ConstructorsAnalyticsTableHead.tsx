import TableRow from '@mui/material/TableRow';
import { TableHead } from '@mui/material';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

const ConstructorsAnalyticsTableHead: React.FunctionComponent = () => (
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
