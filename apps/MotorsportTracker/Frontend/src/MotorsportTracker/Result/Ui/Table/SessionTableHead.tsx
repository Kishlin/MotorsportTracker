'use client';

import { FunctionComponent } from 'react';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type SessionTableHeadProps = {
    withComparison: boolean
};

const SessionTableHead: FunctionComponent<SessionTableHeadProps> = ({ withComparison }) => (
    <TableHead>
        <TableRow>
            <StyledTableCell>Pos</StyledTableCell>
            <StyledTableCell>N°</StyledTableCell>
            <StyledTableCell>Driver</StyledTableCell>
            <StyledTableCell>Team</StyledTableCell>
            <StyledTableCell align="right">Laps</StyledTableCell>
            <StyledTableCell align="right">Time</StyledTableCell>
            {withComparison && (
                <>
                    <StyledTableCell align="right">Gap</StyledTableCell>
                    <StyledTableCell align="right">Interval</StyledTableCell>
                    <StyledTableCell align="right">Best</StyledTableCell>
                </>
            )}
        </TableRow>
    </TableHead>
);

export default SessionTableHead;
