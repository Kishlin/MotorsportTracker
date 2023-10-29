'use client';

import {
    Paper,
    Table, TableBody,
    TableContainer,
    TableHead,
    TableRow,
} from '@mui/material';
import { styled } from '@mui/material/styles';
import Typography from '@mui/material/Typography';
import { FunctionComponent, useEffect, useState } from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { Series } from '../../../Shared/Types';
import seriesListApi from '../../Api/SeriesListApi';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

const SeriesTable: FunctionComponent = () => {
    const [series, setSeries] = useState<Array<Series>>([]);

    useEffect(
        () => {
            seriesListApi().then(setSeries);
        },
        [],
    );

    if (0 === series.length) {
        return (
            <Typography>There are no series at that time.</Typography>
        );
    }

    const content = series.map(
        (item: Series) => (
            <StyledTableRow key={item.id}>
                <StyledTableCell align="left">{item.name}</StyledTableCell>
                <StyledTableCell align="right">Actions</StyledTableCell>
            </StyledTableRow>
        ),
    );

    return (
        <TableContainer component={Paper}>
            <Table aria-label="standings-table">
                <TableHead>
                    <TableRow>
                        <StyledTableCell align="left">Name</StyledTableCell>
                        <StyledTableCell align="right">Actions</StyledTableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {content}
                </TableBody>
            </Table>
        </TableContainer>
    );
};

export default SeriesTable;
