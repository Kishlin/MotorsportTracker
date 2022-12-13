import { Typography } from '@mui/material';
import React from 'react';

declare type CalendarDayTitleProps = {
    isAnotherDayThanRefMonth: boolean,
    children: string,
};

const CalendarDayDateTitle: React.FunctionComponent<CalendarDayTitleProps> = ({
    isAnotherDayThanRefMonth,
    children,
}) => {
    const color = isAnotherDayThanRefMonth ? 'text.secondary' : 'text.primary';

    return (
        <Typography align="center" sx={{ color }}>{children}</Typography>
    );
};

export default CalendarDayDateTitle;
