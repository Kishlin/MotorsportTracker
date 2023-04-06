import TableContainer from '@mui/material/TableContainer';
import Table from '@mui/material/Table';
import Paper from '@mui/material/Paper';
import React from 'react';

declare type RaceResultProps = {
    children: React.ReactNode,
};

const SessionTable: React.FunctionComponent<RaceResultProps> = ({ children }) => (
    <TableContainer component={Paper}>
        <Table aria-label="results-table">
            {children}
        </Table>
    </TableContainer>
);

export default SessionTable;
