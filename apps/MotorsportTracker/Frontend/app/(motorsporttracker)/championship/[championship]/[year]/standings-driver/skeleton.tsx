'use client';

import Skeleton from '@mui/material/Skeleton';
import { FunctionComponent } from 'react';
import Box from '@mui/material/Box';

const PageSkeleton: FunctionComponent = () => (
    <Box sx={{ mx: 8, mt: 4 }}>
        <Skeleton variant="rectangular" animation="wave" width={150} height={32} sx={{ borderRadius: 1, mb: 2 }} />
        <Skeleton variant="rectangular" animation="wave" width={500} height={1200} sx={{ borderRadius: 3 }} />
    </Box>
);

export default PageSkeleton;
