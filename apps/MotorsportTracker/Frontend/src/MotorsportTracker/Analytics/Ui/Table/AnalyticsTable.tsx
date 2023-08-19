'use client';

import React, { FunctionComponent } from 'react';

import TableContainer from '@mui/material/TableContainer';
import Table from '@mui/material/Table';
import Paper from '@mui/material/Paper';

declare type RaceResultProps = {
    children: React.ReactNode,
};

const AnalyticsTable: FunctionComponent<RaceResultProps> = ({ children }) => (
    <TableContainer component={Paper}>
        <Table aria-label="standings-table">
            {children}
        </Table>
    </TableContainer>
);

export default AnalyticsTable;
