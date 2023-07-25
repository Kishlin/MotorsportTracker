import TableCell, { tableCellClasses, TableCellProps } from '@mui/material/TableCell';
import { styled } from '@mui/material/styles';
import React from 'react';

const StyledComponent = styled(TableCell)(({ theme }) => ({
    [`&.${tableCellClasses.head}`]: {
        backgroundColor: theme.palette.common.black,
        color: theme.palette.common.white,
    },
    [`&.${tableCellClasses.body}`]: {
        fontSize: 14,
    },
}));

declare type SessionTableCellProps = TableCellProps & {
    children: React.ReactNode | string,
};

const StyledTableCell: React.FunctionComponent<SessionTableCellProps> = ({ children }) => (
    <StyledComponent>{children}</StyledComponent>
);

export default StyledTableCell;
