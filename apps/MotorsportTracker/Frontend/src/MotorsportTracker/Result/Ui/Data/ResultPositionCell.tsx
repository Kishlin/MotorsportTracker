import Typography from '@mui/material/Typography';
import React from 'react';

import { ResultClassification } from '../../Types/Index';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultPositionProps = {
    classifiedStatus: ResultClassification,
    position: number,
};

const ResultPositionCell: React.FunctionComponent<ResultPositionProps> = ({ position, classifiedStatus }) => (
    <StyledTableCell>
        <Typography>{0 === position ? classifiedStatus : position.toString()}</Typography>
    </StyledTableCell>
);

export default ResultPositionCell;
