import Typography from '@mui/material/Typography';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { Country } from '../../../../Shared/Types/Index';

declare type ResultEntryProps = {
    country: null|Country,
    name: string,
};

const ResultEntryCell: React.FunctionComponent<ResultEntryProps> = ({ country, name }) => {
    const content = null !== country && null !== country.code
        ? (
            <>
                <img
                    src={`/assets/flags/1x1/${country.code}.svg`}
                    style={{ verticalAlign: 'text-bottom' }}
                    alt={country.code}
                    height={20}
                />
                <span style={{ marginLeft: '10px' }}>{name}</span>
            </>
        )
        : (<span style={{ marginLeft: '30px' }}>{name}</span>);

    return (
        <StyledTableCell>
            <Typography noWrap>
                {content}
            </Typography>
        </StyledTableCell>
    );
};

export default ResultEntryCell;
