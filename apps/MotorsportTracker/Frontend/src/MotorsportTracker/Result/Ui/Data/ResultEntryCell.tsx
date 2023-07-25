import Typography from '@mui/material/Typography';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultEntryProps = {
    countryCode: string,
    name: string,
};

const ResultEntryCell: React.FunctionComponent<ResultEntryProps> = ({ countryCode, name }) => (
    <StyledTableCell>
        <Typography noWrap>
            <img
                src={`/assets/flags/1x1/${countryCode}.svg`}
                style={{ verticalAlign: 'text-bottom' }}
                alt={countryCode}
                height={20}
            />
            <span style={{ marginLeft: '5px' }}>{name}</span>
        </Typography>
    </StyledTableCell>
);

export default ResultEntryCell;
