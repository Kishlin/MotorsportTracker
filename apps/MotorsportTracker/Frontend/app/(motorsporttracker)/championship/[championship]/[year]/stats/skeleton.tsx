'use client';

import Skeleton from '@mui/material/Skeleton';
import { FunctionComponent } from 'react';
import Box from '@mui/material/Box';


const PageSkeleton: FunctionComponent = () => (
    <Box sx={{ mx: 4, mt: 2 }}>
        <Skeleton variant="rectangular" animation="wave" width="100%" height={700} sx={{ borderRadius: 5, mb: 4 }} />
        <Skeleton variant="rectangular" animation="wave" width="100%" height={400} sx={{ borderRadius: 5, mb: 4 }} />
        <Skeleton variant="rectangular" animation="wave" width="100%" height={200} sx={{ borderRadius: 5 }} />
    </Box>
);

export default PageSkeleton;
