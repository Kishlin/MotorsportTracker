import Typography from '@mui/material/Typography';
import React from 'react';

import SessionTableCell from '../Table/SessionTableCell';
import { ResultClassification } from '../../Types/Index';

declare type ResultPositionProps = {
    classifiedStatus: ResultClassification,
    position: number,
};

const ResultPositionCell: React.FunctionComponent<ResultPositionProps> = ({ position, classifiedStatus }) => (
    <SessionTableCell>
        <Typography>{0 === position ? classifiedStatus : position.toString()}</Typography>
    </SessionTableCell>
);

export default ResultPositionCell;
