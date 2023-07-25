import TableRow from '@mui/material/TableRow';
import { TableHead } from '@mui/material';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { StandingType } from '../../../Shared/Types';

declare type StandingsTableHeadProps = {
    type: StandingType,
};

const StandingsTableHead: React.FunctionComponent<StandingsTableHeadProps> = ({ type }) => (
    <TableHead>
        <TableRow>
            <StyledTableCell align="left">Position</StyledTableCell>
            <StyledTableCell>{`${type.slice(0, 1).toUpperCase()}${type.slice(1)}`}</StyledTableCell>
            <StyledTableCell align="right">Points</StyledTableCell>
        </TableRow>
    </TableHead>
);

export default StandingsTableHead;
