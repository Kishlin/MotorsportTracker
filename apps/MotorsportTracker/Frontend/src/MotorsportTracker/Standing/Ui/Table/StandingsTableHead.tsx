'use client';

import { FunctionComponent } from 'react';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { StandingType } from '../../../Shared/Types';

declare type StandingsTableHeadProps = {
    type: StandingType,
};

const StandingsTableHead: FunctionComponent<StandingsTableHeadProps> = ({ type }) => (
    <TableHead>
        <TableRow>
            <StyledTableCell align="left">Position</StyledTableCell>
            <StyledTableCell>{`${type.slice(0, 1).toUpperCase()}${type.slice(1)}`}</StyledTableCell>
            <StyledTableCell align="right">Points</StyledTableCell>
        </TableRow>
    </TableHead>
);

export default StandingsTableHead;
