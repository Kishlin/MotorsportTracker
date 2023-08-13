import React, { useState } from 'react';

import GraphContainer from '../../Shared/Ui/GraphContainer';
import { HistoriesData, HistoriesSeries } from '../Types';
import HistoriesLegend from './HistoriesLegend';
import Canvas from '../../../Canvas/Ui/Canvas';
import HistoriesTitle from './HistoriesTitle';

declare type HistoriesGraphProps = {
    data: HistoriesData,
    isMultiDriver: boolean,
};

const axisColor = '#ffffff';

const axisMargin = 50;
const tickStart = 40;
const labelsMargin = 25;
const axisNameMargin = 10;
const lineDash = [
    [0, 0],
    [6, 6],
    [2, 2],
];

const HistoriesGraph: React.FunctionComponent<HistoriesGraphProps> = ({ data, isMultiDriver }) => {
    const showSeries: {[key: number]: boolean} = {};

    data.series.forEach((series: HistoriesSeries) => {
        showSeries[series.car_number] = true;
    });

    const [seriesShowStatus, setSeriesShowStatus] = useState<{[key: string]: boolean}>(showSeries);

    const toggleSeries = (index: number) => {
        setSeriesShowStatus({ ...seriesShowStatus, [index]: !seriesShowStatus[index] });
    };

    const { laps, series } = data;

    const lapsTotal = laps;

    const highest = data.series.length + 4;
    const lowest = 1;

    const positionSpan = highest - lowest;

    const drawYAxis = (ctx: CanvasRenderingContext2D, pixelToPositionRatio: number, width: number, height: number) => {
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, 0);
        ctx.lineTo(axisMargin, height - axisMargin);
        ctx.stroke();
        ctx.restore();

        const limit = Math.floor(lowest) + 2;

        for (let i = Math.floor(highest) - 2; i >= limit; i -= 1) {
            const yPos = (highest - i) * pixelToPositionRatio;

            ctx.fillText((highest - i - 1).toString(), labelsMargin + tickStart / 3, yPos);
            ctx.restore();
        }

        ctx.save();
        ctx.rotate(-Math.PI / 2);
        ctx.fillText('Position', (axisMargin - width) / 4, axisNameMargin);
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

    const drawSeries = (ctx: CanvasRenderingContext2D, pixelToPositionRatio: number, pixelToLapsRatio: number) => {
        ctx.lineWidth = 2;

        series.forEach((currentSeries: HistoriesSeries) => {
            if (false === seriesShowStatus[currentSeries.car_number]) {
                return;
            }

            ctx.save();
            ctx.beginPath();
            ctx.setLineDash(lineDash[currentSeries.index % 3]);

            ctx.strokeStyle = currentSeries.color;

            let previous: number|undefined;

            Object.keys(currentSeries.positions).forEach((key: string) => {
                const lap = parseInt(key, 10);

                if (undefined === previous || previous !== lap - 1) {
                    ctx.moveTo(
                        axisMargin + lap * pixelToLapsRatio,
                        (1 + currentSeries.positions[lap]) * pixelToPositionRatio,
                    );
                } else {
                    ctx.lineTo(
                        axisMargin + lap * pixelToLapsRatio,
                        (1 + currentSeries.positions[lap]) * pixelToPositionRatio,
                    );
                }

                previous = lap;
            });

            ctx.stroke();
            ctx.restore();
        });
    };

    const drawHistoriesGraph = (ctx: CanvasRenderingContext2D) => {
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';
        ctx.strokeStyle = axisColor;
        ctx.fillStyle = axisColor;
        ctx.lineWidth = 1;

        const { width, height } = ctx.canvas;
        const pixelToLapsRatio = (width - axisMargin) / lapsTotal;
        const pixelToPositionRatio = (height - axisMargin) / positionSpan;

        drawYAxis(ctx, pixelToPositionRatio, width, height);
        drawXAxis(ctx, pixelToLapsRatio, width, height);

        drawSeries(ctx, pixelToPositionRatio, pixelToLapsRatio);
    };

    return (
        <GraphContainer>
            <HistoriesTitle type={data.session.type} />
            <Canvas draw={drawHistoriesGraph} aspectRatio={2} />
            <HistoriesLegend
                series={series}
                isMultiDriver={isMultiDriver}
                toggleSeries={toggleSeries}
                seriesShowStatus={seriesShowStatus}
            />
        </GraphContainer>
    );
};

export default HistoriesGraph;
