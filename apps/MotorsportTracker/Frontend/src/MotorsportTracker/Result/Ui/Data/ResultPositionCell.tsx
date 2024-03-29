'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

import { ResultClassification } from '../../Types/Index';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultPositionProps = {
    classifiedStatus: ResultClassification,
    position: number,
};

const ResultPositionCell: FunctionComponent<ResultPositionProps> = ({ position, classifiedStatus }) => (
    <StyledTableCell>
        <Typography>{0 === position || 1000 < position ? classifiedStatus : position.toString()}</Typography>
    </StyledTableCell>
);

export default ResultPositionCell;
