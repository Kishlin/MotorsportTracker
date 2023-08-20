'use client';

import HorizontalRuleIcon from '@mui/icons-material/HorizontalRule';
import LinearScaleIcon from '@mui/icons-material/LinearScale';
import MoreHorizIcon from '@mui/icons-material/MoreHoriz';
import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';
import Grid from '@mui/material/Grid';

import { HistoriesSeries } from '../Types';

declare type HistoriesLegendProps = {
    series: Array<HistoriesSeries>,
    toggleSeries: (series: number) => void,
    seriesShowStatus: {[key: string]: boolean},
    isMultiDriver: boolean,
};

const disabledSeriesColor = '#666';

const HistoriesLegend: FunctionComponent<HistoriesLegendProps> = ({
    series,
    toggleSeries,
    seriesShowStatus,
    isMultiDriver,
}) => {
    const legendIcon = ((entry: HistoriesSeries) => {
        const color = seriesShowStatus[entry.car_number] ? entry.color : disabledSeriesColor;
        const applicableIndex = entry.index % 3;

        if (2 === applicableIndex) {
            return <LinearScaleIcon style={{ color }} />;
        }

        if (1 === applicableIndex) {
            return <MoreHorizIcon style={{ color }} />;
        }

        return <HorizontalRuleIcon style={{ color }} />;
    });

    const legend = series.map((entry) => (
        <Grid key={entry.car_number} item sx={{ px: 1 }}>
            <Grid container justifyContent="center" sx={{ cursor: 'pointer' }} onClick={() => toggleSeries(entry.car_number)}>
                <Grid item sx={{ mt: '-1px', mr: 1 }}>
                    {legendIcon(entry)}
                </Grid>
                <Grid item>
                    <Typography style={{ color: seriesShowStatus[entry.car_number] ? 'inherit' : disabledSeriesColor }}>
                        {isMultiDriver ? entry.car_number : entry.short_code}
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

export default HistoriesLegend;
