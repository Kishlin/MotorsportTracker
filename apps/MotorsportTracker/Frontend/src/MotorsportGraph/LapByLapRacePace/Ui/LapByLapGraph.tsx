import React, { useState } from 'react';

import GraphContainer from '../../Shared/Ui/GraphContainer';
import { LapByLapGraphData, LapByLapSeries } from '../../Shared/Types';
import Canvas from '../../../Canvas/Ui/Canvas';
import LapByLapLegend from './LapByLapLegend';
import LapByLapTitle from './LapByLapTitle';

declare type LapByLapGraphProps = {
    data: LapByLapGraphData,
};

const axisColor = '#ffffff';

const lapTimeSpace = 500;
const axisMargin = 50;
const tickStart = 40;
const labelsMargin = 25;
const axisNameMargin = 10;
const lineDash = [3, 3];

const LapByLapGraph: React.FunctionComponent<LapByLapGraphProps> = ({ data }) => {
    const showSeries: {[key: string]: boolean} = {};

    data.series.forEach((series: LapByLapSeries) => {
        showSeries[series.label] = true;
    });

    const [seriesShowStatus, setSeriesShowStatus] = useState<{[key: string]: boolean}>(showSeries);

    const toggleSeries = (label: string) => {
        setSeriesShowStatus({ ...seriesShowStatus, [label]: !seriesShowStatus[label] });
    };

    const { laps, lapTimes, series } = data;

    const highest = (lapTimes.slowest + lapTimeSpace);
    const lowest = (lapTimes.fastest - lapTimeSpace);
    const lapsTotal = laps + 5;

    const timeSpan = highest - lowest;

    const drawYAxis = (ctx: CanvasRenderingContext2D, pixelToMilliRatio: number, width: number, height: number) => {
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, 0);
        ctx.lineTo(axisMargin, height - axisMargin);
        ctx.stroke();
        ctx.restore();

        const limit = Math.floor(lowest);

        for (let i = Math.floor(highest / 1000) * 1000; i >= limit; i -= 1000) {
            const yPos = (highest - i) * pixelToMilliRatio;

            ctx.beginPath();
            ctx.moveTo(tickStart, yPos);
            ctx.lineTo(axisMargin, yPos);
            ctx.stroke();

            ctx.fillText((i / 1000).toString(), labelsMargin, yPos);
            ctx.restore();
        }

        ctx.save();
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Lap Time (s)', (axisMargin - width) / 4, axisNameMargin);
        ctx.restore();
    };

    const drawXAxis = (ctx: CanvasRenderingContext2D, pixelToLapsRatio: number, width: number, height: number) => {
        const marginBottom = height - axisMargin;
        const tickEndYPos = height - tickStart;
        const labelYPos = height - labelsMargin;

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, marginBottom);
        ctx.lineTo(width, marginBottom);
        ctx.stroke();
        ctx.restore();

        for (let i = 5; i < laps; i += 5) {
            const xPos = axisMargin + i * pixelToLapsRatio;

            ctx.beginPath();
            ctx.moveTo(xPos, marginBottom);
            ctx.lineTo(xPos, tickEndYPos);
            ctx.stroke();

            ctx.fillText(i.toString(), xPos, labelYPos);
            ctx.restore();
        }

        ctx.save();
        ctx.fillText('Lap', (width - axisMargin) / 2, height - axisNameMargin);
        ctx.restore();
    };

    const drawSeries = (ctx: CanvasRenderingContext2D, pixelToMilliRatio: number, pixelToLapsRatio: number) => {
        series.forEach((currentSeries: LapByLapSeries) => {
            if (false === seriesShowStatus[currentSeries.label]) {
                return;
            }

            ctx.save();
            ctx.beginPath();
            ctx.setLineDash(currentSeries.dashed ? lineDash : []);

            ctx.strokeStyle = currentSeries.color;

            let previous: number|undefined;

            Object.keys(currentSeries.lapTimes).forEach((key: string) => {
                const lap = parseInt(key, 10);

                if (undefined === previous || previous !== lap - 1) {
                    ctx.moveTo(
                        axisMargin + lap * pixelToLapsRatio,
                        (highest - currentSeries.lapTimes[lap]) * pixelToMilliRatio,
                    );
                } else {
                    ctx.lineTo(
                        axisMargin + lap * pixelToLapsRatio,
                        (highest - currentSeries.lapTimes[lap]) * pixelToMilliRatio,
                    );
                }

                previous = lap;
            });

            ctx.stroke();
            ctx.restore();
        });
    };

    const drawLapByLapGraph = (ctx: CanvasRenderingContext2D) => {
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';
        ctx.strokeStyle = axisColor;
        ctx.fillStyle = axisColor;
        ctx.lineWidth = 1;

        const { width, height } = ctx.canvas;
        const pixelToLapsRatio = (width - axisMargin) / lapsTotal;
        const pixelToMilliRatio = (height - axisMargin) / timeSpan;

        drawYAxis(ctx, pixelToMilliRatio, width, height);
        drawXAxis(ctx, pixelToLapsRatio, width, height);

        drawSeries(ctx, pixelToMilliRatio, pixelToLapsRatio);
    };

    return (
        <GraphContainer>
            <LapByLapTitle type={data.session.type} />
            <Canvas draw={drawLapByLapGraph} aspectRatio={2} />
            <LapByLapLegend series={series} toggleSeries={toggleSeries} seriesShowStatus={seriesShowStatus} />
        </GraphContainer>
    );
};

export default LapByLapGraph;