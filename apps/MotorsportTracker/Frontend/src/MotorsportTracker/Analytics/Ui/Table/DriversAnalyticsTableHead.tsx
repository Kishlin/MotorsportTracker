'use client';

import TableRow from '@mui/material/TableRow';
import Tooltip from '@mui/material/Tooltip';
import { TableHead } from '@mui/material';
import { FunctionComponent } from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

const DriversAnalyticsTableHead: FunctionComponent = () => (
    <TableHead>
        <TableRow>
            <StyledTableCell align="left">Position</StyledTableCell>
            <StyledTableCell align="left">Driver</StyledTableCell>
            <StyledTableCell align="right">Points</StyledTableCell>
            <StyledTableCell align="right">
                <Tooltip title="Average Finish Position">
                    <span>Avg Pos</span>
                </Tooltip>
            </StyledTableCell>
            <StyledTableCell align="right">
                <Tooltip title="Fastest Laps">
                    <span>FL</span>
                </Tooltip>
            </StyledTableCell>
            <StyledTableCell align="right">Podiums</StyledTableCell>
            <StyledTableCell align="right">Poles</StyledTableCell>
            <StyledTableCell align="right">DNF</StyledTableCell>
            <StyledTableCell align="right">Starts</StyledTableCell>
            <StyledTableCell align="right">Top 10</StyledTableCell>
            <StyledTableCell align="right">Top 5</StyledTableCell>
            <StyledTableCell align="right">Class Wins</StyledTableCell>
            <StyledTableCell align="right">Wins</StyledTableCell>
            <StyledTableCell align="right">Wins %</StyledTableCell>
        </TableRow>
    </TableHead>
);

export default DriversAnalyticsTableHead;
