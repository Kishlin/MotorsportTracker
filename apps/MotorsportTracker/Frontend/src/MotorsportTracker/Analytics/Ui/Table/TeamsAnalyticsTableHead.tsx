import TableRow from '@mui/material/TableRow';
import Tooltip from '@mui/material/Tooltip';
import { TableHead } from '@mui/material';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

const TeamsAnalyticsTableHead: React.FunctionComponent = () => (
    <TableHead>
        <TableRow>
            <StyledTableCell align="left">Position</StyledTableCell>
            <StyledTableCell align="left">Team</StyledTableCell>
            <StyledTableCell align="right">Points</StyledTableCell>
            <StyledTableCell align="right">
                <Tooltip title="Fastest Laps">
                    <span>FL</span>
                </Tooltip>
            </StyledTableCell>
            <StyledTableCell align="right">
                <Tooltip title="1-2 finish">
                    <span>1-2</span>
                </Tooltip>
            </StyledTableCell>
            <StyledTableCell align="right">Podiums</StyledTableCell>
            <StyledTableCell align="right">Poles</StyledTableCell>
            <StyledTableCell align="right">DNF</StyledTableCell>
            <StyledTableCell align="right">Starts</StyledTableCell>
            <StyledTableCell align="right">Top 10</StyledTableCell>
            <StyledTableCell align="right">Top 5</StyledTableCell>
            <StyledTableCell align="right">Wins</StyledTableCell>
            <StyledTableCell align="right">Wins %</StyledTableCell>
        </TableRow>
    </TableHead>
);

export default TeamsAnalyticsTableHead;
