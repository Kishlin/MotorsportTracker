'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

declare type HistoriesListTitleProps = {
    eventName: string,
}

const HistoriesListTitle: FunctionComponent<HistoriesListTitleProps> = ({ eventName }) => (
    <Typography variant="h4" align="left" sx={{ my: 4 }}>{`${eventName} - Histories`}</Typography>
);

export default HistoriesListTitle;
