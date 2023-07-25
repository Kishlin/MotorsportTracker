import TableRow from '@mui/material/TableRow';
import { styled } from '@mui/material/styles';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import StandingStandeeCell from '../Data/StandingStandeeCell';
import { Standing } from '../../../Shared/Types';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

declare type StandingsTableStandingProps = {
    standing: Standing,
};

const StandingsTableStanding: React.FunctionComponent<StandingsTableStandingProps> = ({ standing }) => (
    <StyledTableRow>
        <StyledTableCell>{standing.position.toString()}</StyledTableCell>
        <StandingStandeeCell name={standing.name} country={standing.country} />
        <StyledTableCell>{standing.points.toString()}</StyledTableCell>
    </StyledTableRow>
);

export default StandingsTableStanding;
