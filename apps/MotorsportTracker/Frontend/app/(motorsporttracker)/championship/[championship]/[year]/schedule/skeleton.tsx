'use client';

import { FunctionComponent } from 'react';
import Box from '@mui/material/Box';

import ScheduleSkeleton from '../../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleSkeleton';

const PageSkeleton: FunctionComponent = () => (
    <Box sx={{ mx: 4 }}>
        <ScheduleSkeleton />
    </Box>
);

export default PageSkeleton;
