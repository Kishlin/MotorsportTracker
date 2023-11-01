'use client';

import {
    Button,
    Paper,
    Table,
    TableRow,
    TableHead,
    TableBody,
    TableContainer,
} from '@mui/material';
import {
    useEffect,
    useContext,
    FunctionComponent,
} from 'react';
import { styled } from '@mui/material/styles';
import Typography from '@mui/material/Typography';
import { faEye } from '@fortawesome/free-solid-svg-icons/faEye';

import { SeriesContext, SeriesContextType } from '../../Context/SeriesContext';
import FontAwesomeSvgIcon from '../../../../Shared/Ui/Icon/FontAwesomeSvgIcon';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import SeriesSeasonsButton from './SeriesSeasonsButton';
import { Series } from '../../../Shared/Types';
import AdminLink from '../../../../Shared/Ui/Navigation/AdminLink';

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
                    <SeriesSeasonsButton series={item} />
                    <Button sx={{ p: 0, display: 'inline-block', height: 24 }}>
                        <AdminLink to={`/series/${item.name}`}>
                            <FontAwesomeSvgIcon icon={faEye} />
                        </AdminLink>
                    </Button>
                </StyledTableCell>
            </StyledTableRow>
        ),
    );

    return (
        <TableContainer component={Paper}>
            <Table aria-label="series-table">
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
