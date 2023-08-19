'use client';

import TableRow from '@mui/material/TableRow';
import { styled } from '@mui/material/styles';
import { FunctionComponent } from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import StandingStandeeCell from '../../../Standing/Ui/Data/StandingStandeeCell';
import { ConstructorAnalytics } from '../../Types/Index';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

declare type ConstructorsAnalyticsRowProps = {
    analytics: ConstructorAnalytics,
};

const ConstructorsAnalyticsRow: FunctionComponent<ConstructorsAnalyticsRowProps> = ({ analytics }) => (
    <StyledTableRow>
        <StyledTableCell>{analytics.position.toString()}</StyledTableCell>
        <StandingStandeeCell name={analytics.name} country={analytics.country} />
        <StyledTableCell>{analytics.points.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.wins.toString()}</StyledTableCell>
    </StyledTableRow>
);

export default ConstructorsAnalyticsRow;
