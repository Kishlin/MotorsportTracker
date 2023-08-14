import React from 'react';

import formatTime from '../../../MotorsportTracker/Result/Utils/FormatTime';
import { FastestLapGraphData, FastestLapSeries } from '../../Shared/Types';
import GraphContainer from '../../Shared/Ui/GraphContainer';
import FastestLapTitle from './FastestLapTitle';
import Canvas from '../../../Canvas/Ui/Canvas';

declare type FastestLapGraphProps = {
    data: FastestLapGraphData,
    isMultiDriver: boolean,
};

const axisColor = '#ffffff';
const axisInGraphColor = 'rgba(255,255,255,0.41)';
const axisInGraphDarkerColor = 'rgba(255,255,255,0.21)';

const axisMargin = 50;
const tickStart = 40;
const labelsMargin = 25;
const axisNameMargin = 10;
const seriesRowSpacing = 8;
const marginSeconds = 1;

const FastestLapGraph: React.FunctionComponent<FastestLapGraphProps> = ({ data, isMultiDriver }) => {
    const { maxDelta } = data;

    const seriesCount = data.series.length;
    const seriesSpan = seriesCount + 1;
    const maxDisplayedDelta = Math.ceil(maxDelta / 1000) + marginSeconds;

    const drawYAxis = (ctx: CanvasRenderingContext2D, pixelToSeriesRatio: number, width: number, height: number) => {
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, 0);
        ctx.lineTo(axisMargin, height - axisMargin);
        ctx.stroke();
        ctx.restore();

        ctx.save();

        data.series.forEach((series: FastestLapSeries, index: number) => {
            ctx.fillStyle = series.color;
            ctx.fillText(
                isMultiDriver ? series.car_number.toString() : series.short_code.slice(0, 3),
                labelsMargin,
                (index + 1) * pixelToSeriesRatio,
            );
        });

        ctx.restore();
    };

    const drawXAxis = (ctx: CanvasRenderingContext2D, pixelToSecondRatio: number, width: number, height: number) => {
        const marginBottom = height - axisMargin;
        const tickEndYPos = height - tickStart;
        const labelYPos = height - labelsMargin;

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, marginBottom);
        ctx.lineTo(width, marginBottom);
        ctx.stroke();
        ctx.restore();

        for (let i = 0; i < maxDisplayedDelta; i += 1) {
            const xPos = axisMargin + i * pixelToSecondRatio;

            ctx.strokeStyle = axisColor;
            ctx.beginPath();
            ctx.moveTo(xPos, marginBottom);
            ctx.lineTo(xPos, tickEndYPos);
            ctx.stroke();

            ctx.fillText(i.toString(), xPos, labelYPos);

            ctx.setLineDash([2, 2]);

            ctx.beginPath();
            ctx.strokeStyle = axisInGraphColor;
            ctx.moveTo(xPos, marginBottom);
            ctx.lineTo(xPos, 0);
            ctx.stroke();

            ctx.beginPath();
            ctx.strokeStyle = axisInGraphDarkerColor;
            ctx.moveTo(xPos + i + 0.2 * pixelToSecondRatio, marginBottom);
            ctx.lineTo(xPos + i + 0.2 * pixelToSecondRatio, 0);
            ctx.moveTo(xPos + i + 0.4 * pixelToSecondRatio, marginBottom);
            ctx.lineTo(xPos + i + 0.4 * pixelToSecondRatio, 0);
            ctx.moveTo(xPos + i + 0.6 * pixelToSecondRatio, marginBottom);
            ctx.lineTo(xPos + i + 0.6 * pixelToSecondRatio, 0);
            ctx.moveTo(xPos + i + 0.8 * pixelToSecondRatio, marginBottom);
            ctx.lineTo(xPos + i + 0.8 * pixelToSecondRatio, 0);
            ctx.stroke();
        }

        ctx.save();
        ctx.fillText('Laptime Delta from Fastest (seconds)', (width - axisMargin) / 2, height - axisNameMargin);
        ctx.restore();
    };

    const drawSeries = (ctx: CanvasRenderingContext2D, pixelToSeriesRatio: number, pixelToSecondRatio: number) => {
        ctx.save();
        ctx.font = '15px Roboto';

        data.series.forEach((currentSeries: FastestLapSeries, seriesIndex: number) => {
            const { fastest, delta } = currentSeries;

            const stintHeight = pixelToSeriesRatio - seriesRowSpacing;
            const rectangleUpperY = (seriesIndex + 1) * pixelToSeriesRatio - (stintHeight / 2);

            const deltaBarLength = (delta / 1000) * pixelToSecondRatio;

            ctx.fillStyle = axisColor;
            ctx.textAlign = 'left';
            ctx.fillText(
                formatTime(fastest),
                axisMargin + deltaBarLength + 10,
                rectangleUpperY + stintHeight / 2,
            );

            if (0 < delta) {
                ctx.fillStyle = currentSeries.color;

                ctx.fillRect(
                    axisMargin,
                    rectangleUpperY,
                    deltaBarLength,
                    stintHeight,
                );
            }
        });

        ctx.restore();
    };

    const drawFastestLapGraph = (ctx: CanvasRenderingContext2D) => {
        ctx.textBaseline = 'middle';
        ctx.strokeStyle = axisColor;
        ctx.fillStyle = axisColor;
        ctx.textAlign = 'center';
        ctx.font = '15px Roboto';
        ctx.lineWidth = 1;

        const { width, height } = ctx.canvas;
        const pixelToSecondRatio = (width - axisMargin) / maxDisplayedDelta;
        const pixelToSeriesRatio = (height - axisMargin) / seriesSpan;

        drawYAxis(ctx, pixelToSeriesRatio, width, height);
        drawXAxis(ctx, pixelToSecondRatio, width, height);

        drawSeries(ctx, pixelToSeriesRatio, pixelToSecondRatio);
    };

    return (
        <GraphContainer>
            <FastestLapTitle type={data.session.type} />
            <Canvas draw={drawFastestLapGraph} aspectRatio={1.7} />
        </GraphContainer>
    );
};

export default FastestLapGraph;
