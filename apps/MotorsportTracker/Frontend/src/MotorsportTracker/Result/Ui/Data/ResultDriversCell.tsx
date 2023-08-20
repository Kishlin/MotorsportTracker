'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { ResultDriver } from '../../Types/Index';

declare type ResultDriversProps = {
    driver: ResultDriver,
    additionalDrivers: ResultDriver[],
};

const row = (driver: ResultDriver) => {
    if (null === driver.country || null === driver.country.code) {
        return (<span style={{ display: 'block', marginLeft: '30px' }}>{driver.name}</span>);
    }

    return (
        <span>
            <img
                src={`/assets/flags/1x1/${driver.country.code}.svg`}
                style={{ verticalAlign: 'text-bottom' }}
                alt={driver.country.code ?? 'Country'}
                height={20}
            />
            <span style={{ marginLeft: '10px' }}>{driver.name}</span>
        </span>
    );
};

const ResultDriversCell: FunctionComponent<ResultDriversProps> = ({ driver, additionalDrivers }) => (
    <StyledTableCell>
        <Typography noWrap>
            {row(driver)}
        </Typography>
        {additionalDrivers.map((additionalDriver: ResultDriver) => (
            <Typography noWrap key={additionalDriver.id}>
                {row(additionalDriver)}
            </Typography>
        ))}
    </StyledTableCell>
);

export default ResultDriversCell;
