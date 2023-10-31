'use client';

import {
    Paper,
    Table,
    TableRow,
    TableHead,
    TableBody,
    TableContainer,
} from '@mui/material';
import { styled } from '@mui/material/styles';
import Typography from '@mui/material/Typography';
import { faEye } from '@fortawesome/free-solid-svg-icons/faEye';
import { FunctionComponent, useContext, useEffect } from 'react';
import { faRotate } from '@fortawesome/free-solid-svg-icons/faRotate';

import { SeriesContext, SeriesContextType } from '../../Context/SeriesContext';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { Series } from '../../../Shared/Types';
import FontAwesomeSvgIcon from '../../../../Shared/Ui/Icon/FontAwesomeSvgIcon';

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
    const { series, refreshSeries } = useContext<SeriesContextType>(SeriesContext);

    useEffect(
        () => {
            refreshSeries();
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
                <StyledTableCell align="right">{item.seasons}</StyledTableCell>
                <StyledTableCell align="right">
                    <FontAwesomeSvgIcon icon={faEye} sx={{ mr: 1 }} />
                    <FontAwesomeSvgIcon icon={faRotate} />
                </StyledTableCell>
            </StyledTableRow>
        ),
    );

    return (
        <TableContainer component={Paper}>
            <Table aria-label="standings-table">
                <TableHead>
                    <TableRow>
                        <StyledTableCell align="left">Name</StyledTableCell>
                        <StyledTableCell align="right">Seasons</StyledTableCell>
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
