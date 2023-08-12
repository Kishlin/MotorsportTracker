import Typography from '@mui/material/Typography';
import React from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { ResultDriver, ResultDriverWithCountry } from '../../Types/Index';

declare type ResultDriversProps = {
    driver: ResultDriverWithCountry,
    additionalDrivers: ResultDriver[],
};

const ResultDriversCell: React.FunctionComponent<ResultDriversProps> = ({ driver, additionalDrivers }) => {
    const content = null !== driver.country
        ? (
            <>
                <img
                    src={`/assets/flags/1x1/${driver.country.code}.svg`}
                    style={{ verticalAlign: 'text-bottom' }}
                    alt={driver.country.code}
                    height={20}
                />
                <span style={{ marginLeft: '10px' }}>{driver.name}</span>
            </>
        )
        : (<span style={{ marginLeft: '30px' }}>{driver.name}</span>);

    const others = 0 < additionalDrivers.length
        ? additionalDrivers.map((additionalDriver: ResultDriver) => (
            <>
                <br />
                <span style={{ marginLeft: '30px' }}>{additionalDriver.name}</span>
            </>
        ))
        : (<noscript />);

    return (
        <StyledTableCell>
            <Typography noWrap>
                {content}
                {others}
            </Typography>
        </StyledTableCell>
    );
};

export default ResultDriversCell;
