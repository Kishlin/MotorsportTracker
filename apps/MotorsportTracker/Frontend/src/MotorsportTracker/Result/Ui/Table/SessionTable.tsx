'use client';

import { FunctionComponent, ReactNode } from 'react';
import TableContainer from '@mui/material/TableContainer';
import Table from '@mui/material/Table';
import Paper from '@mui/material/Paper';

declare type RaceResultProps = {
    children: ReactNode,
};

const SessionTable: FunctionComponent<RaceResultProps> = ({ children }) => (
    <TableContainer component={Paper}>
        <Table aria-label="results-table">
            {children}
        </Table>
    </TableContainer>
);

export default SessionTable;
