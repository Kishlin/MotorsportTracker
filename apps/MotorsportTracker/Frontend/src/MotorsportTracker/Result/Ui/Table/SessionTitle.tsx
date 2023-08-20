'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

declare type RaceResultTitleProps = {
    name: string,
};

const SessionTitle: FunctionComponent<RaceResultTitleProps> = ({ name }) => (
    <Typography variant="h5" align="left" sx={{ my: 4 }}>{name}</Typography>
);

export default SessionTitle;
