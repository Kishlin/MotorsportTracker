import HorizontalRuleIcon from '@mui/icons-material/HorizontalRule';
import MoreHorizIcon from '@mui/icons-material/MoreHoriz';
import Grid from '@mui/material/Grid';
import React from 'react';

import { LapByLapSeries } from '../../Shared/Types';

declare type LapByLapLegendProps = {
    series: Array<LapByLapSeries>,
};

const LapByLapLegend: React.FunctionComponent<LapByLapLegendProps> = ({ series }) => {
    const legendIcon = ((entry: { color: string, dashed: boolean }) => {
        if (entry.dashed) {
            return <MoreHorizIcon style={{ color: entry.color }} />;
        }

        return <HorizontalRuleIcon style={{ color: entry.color }} />;
    });

    const legend = series.map((entry) => (
        <Grid key={entry.label} item xs={1} sx={{ px: 1 }}>
            <Grid container>
                <Grid item sx={{ mt: '-1px', mr: 1 }}>
                    {legendIcon(entry)}
                </Grid>
                <Grid item>
                    {entry.label}
                </Grid>
            </Grid>
        </Grid>
    ));

    return (
        <Grid container columns={{ xs: (series.length + 2) / 3 }} justifyContent="center">
            {legend}
        </Grid>
    );
};

export default LapByLapLegend;
