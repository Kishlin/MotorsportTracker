import TableContainer from '@mui/material/TableContainer';
import Table from '@mui/material/Table';
import Paper from '@mui/material/Paper';
import React from 'react';

declare type RaceResultProps = {
    children: React.ReactNode,
};

const StandingsTable: React.FunctionComponent<RaceResultProps> = ({ children }) => (
    <TableContainer component={Paper}>
        <Table aria-label="standings-table">
            {children}
        </Table>
    </TableContainer>
);

export default StandingsTable;
