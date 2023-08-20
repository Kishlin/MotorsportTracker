'use client';

import Skeleton from '@mui/material/Skeleton';
import { FunctionComponent } from 'react';
import Box from '@mui/material/Box';

const PageSkeleton: FunctionComponent = () => (
    <Box sx={{ mx: 8, mt: 2 }}>
        <Skeleton variant="rectangular" animation="wave" width={250} height={40} sx={{ borderRadius: 1, mb: 2 }} />
        <Skeleton variant="rectangular" animation="wave" width={1300} height={1300} sx={{ borderRadius: 3 }} />
    </Box>
);

export default PageSkeleton;
