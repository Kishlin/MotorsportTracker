import HorizontalRuleIcon from '@mui/icons-material/HorizontalRule';
import MoreHorizIcon from '@mui/icons-material/MoreHoriz';
import Grid from '@mui/material/Grid';
import React from 'react';

declare type LapByLapLegendProps = {
    series: Array<{
        color: string,
        label: string,
        dashed: boolean,
        lapTimes: number[],
    }>,
};

const LapByLapLegend: React.FunctionComponent<LapByLapLegendProps> = ({ series }) => {
    const legendIcon = ((entry: { color: string, dashed: boolean }) => {
        if (entry.dashed) {
            return <MoreHorizIcon style={{ color: entry.color }} />;
        }

        return <HorizontalRuleIcon style={{ color: entry.color }} />;
    });

    const legend = series.map((entry) => (
        <Grid item xs={1} sx={{ px: 1 }}>
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
        <Grid container columns={{ xs: series.length / 2 }} justifyContent="center">
            {legend}
        </Grid>
    );
};

export default LapByLapLegend;
