'use client';

import Skeleton from '@mui/material/Skeleton';
import { FunctionComponent } from 'react';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';

const PageSkeleton: FunctionComponent = () => (
    <Grid container spacing={0} direction="column" sx={{ px: 16, py: 2 }}>
        <Typography variant="h4" align="left" sx={{ my: 4 }}>
            <Skeleton variant="rectangular" animation="wave" width={500} />
        </Typography>
        <Box sx={{ mb: 2 }}>
            <Grid container spacing={0} direction="column" align-items="center" sx={{ px: 16, py: 2 }}>
                <Typography variant="h4" align="left" sx={{ mb: 1 }}>
                    <Skeleton variant="rectangular" animation="wave" width={150} sx={{ mb: 2 }} />
                </Typography>
                <Skeleton variant="rectangular" animation="wave" height={370} />
            </Grid>
        </Box>
        <Box sx={{ mb: 2 }}>
            <Grid container spacing={0} direction="column" align-items="center" sx={{ px: 16, py: 2 }}>
                <Typography variant="h4" align="left" sx={{ mb: 1 }}>
                    <Skeleton variant="rectangular" animation="wave" width={130} sx={{ mb: 2 }} />
                </Typography>
                <Skeleton variant="rectangular" animation="wave" height={350} />
            </Grid>
        </Box>
        <Box sx={{ mb: 2 }}>
            <Grid container spacing={0} direction="column" align-items="center" sx={{ px: 16, py: 2 }}>
                <Typography variant="h4" align="left" sx={{ mb: 1 }}>
                    <Skeleton variant="rectangular" animation="wave" width={200} sx={{ mb: 2 }} />
                </Typography>
                <Skeleton variant="rectangular" animation="wave" height={600} />
            </Grid>
        </Box>
    </Grid>
);

export default PageSkeleton;
