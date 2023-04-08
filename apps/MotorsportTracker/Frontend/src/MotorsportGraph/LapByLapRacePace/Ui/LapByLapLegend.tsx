import HorizontalRuleIcon from '@mui/icons-material/HorizontalRule';
import MoreHorizIcon from '@mui/icons-material/MoreHoriz';
import Typography from '@mui/material/Typography';
import Grid from '@mui/material/Grid';
import React from 'react';

import { LapByLapSeries } from '../../Shared/Types';

declare type LapByLapLegendProps = {
    series: Array<LapByLapSeries>,
    toggleSeries: (series: string) => void,
    seriesShowStatus: {[key: string]: boolean},
};

const disabledSeriesColor = '#666';

const LapByLapLegend: React.FunctionComponent<LapByLapLegendProps> = ({ series, toggleSeries, seriesShowStatus }) => {
    const legendIcon = ((entry: LapByLapSeries) => {
        const color = seriesShowStatus[entry.label] ? entry.color : disabledSeriesColor;

        if (entry.dashed) {
            return <MoreHorizIcon style={{ color }} />;
        }

        return <HorizontalRuleIcon style={{ color }} />;
    });

    const legend = series.map((entry) => (
        <Grid key={entry.label} item sx={{ px: 1 }}>
            <Grid container justifyContent="center" sx={{ cursor: 'pointer' }} onClick={() => toggleSeries(entry.label)}>
                <Grid item sx={{ mt: '-1px', mr: 1 }}>
                    {legendIcon(entry)}
                </Grid>
                <Grid item>
                    <Typography style={{ color: seriesShowStatus[entry.label] ? 'inherit' : disabledSeriesColor }}>
                        {entry.label}
                    </Typography>
                </Grid>
            </Grid>
        </Grid>
    ));

    return (
        <Grid container columns={{ xs: (series.length + 1) / 2 }} justifyContent="center">
            {legend}
        </Grid>
    );
};

export default LapByLapLegend;
