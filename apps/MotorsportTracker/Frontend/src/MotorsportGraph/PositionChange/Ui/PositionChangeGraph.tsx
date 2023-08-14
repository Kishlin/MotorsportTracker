import React from 'react';

import { PositionChangeGraphData, PositionChangeSeries } from '../../Shared/Types';
import GraphContainer from '../../Shared/Ui/GraphContainer';
import PositionChangeTitle from './PositionChangeTitle';
import Canvas from '../../../Canvas/Ui/Canvas';

declare type PositionChangeGraphProps = {
    data: PositionChangeGraphData,
};

const axisColor = '#ffffff';
const axisInGraphColor = 'rgba(255,255,255,0.26)';

const axisMargin = 50;
const tickStart = 40;
const labelsMargin = 25;
const axisNameMargin = 10;
const seriesRowSpacing = 8;
const marginPositions = 1;

const PositionChangeGraph: React.FunctionComponent<PositionChangeGraphProps> = ({ data }) => {
    const { minChanges, maxChanges } = data;

    const seriesCount = data.series.length;
    const seriesSpan = seriesCount + 1;
    const changesSpan = 2 * marginPositions + maxChanges + Math.abs(minChanges);

    const positionOffset = Math.abs(minChanges - marginPositions);

    const drawYAxis = (ctx: CanvasRenderingContext2D, pixelToSeriesRatio: number, width: number, height: number) => {
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, 0);
        ctx.lineTo(axisMargin, height - axisMargin);
        ctx.stroke();
        ctx.restore();

        ctx.save();

        data.series.forEach((series: PositionChangeSeries, index: number) => {
            const yPos = (index + 1) * pixelToSeriesRatio;

            ctx.fillStyle = series.color;
            ctx.fillText(series.short_code.slice(0, 3), labelsMargin, yPos);

            ctx.strokeStyle = axisInGraphColor;
            ctx.setLineDash([2, 2]);
            ctx.beginPath();
            ctx.moveTo(axisMargin, yPos);
            ctx.lineTo(width, yPos);
            ctx.stroke();
        });

        ctx.restore();
    };

    const drawXAxis = (ctx: CanvasRenderingContext2D, pixelToPositionRatio: number, width: number, height: number) => {
        const marginBottom = height - axisMargin;
        const tickEndYPos = height - tickStart;
        const labelYPos = height - labelsMargin;

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, marginBottom);
        ctx.lineTo(width, marginBottom);
        ctx.stroke();
        ctx.restore();

        for (let i = marginPositions; i <= maxChanges + positionOffset; i += 1) {
            const xPos = axisMargin + i * pixelToPositionRatio;

            ctx.strokeStyle = axisColor;
            ctx.beginPath();
            ctx.moveTo(xPos, marginBottom);
            ctx.lineTo(xPos, tickEndYPos);
            ctx.stroke();

            ctx.fillText((i - positionOffset).toString(), xPos, labelYPos);

            ctx.strokeStyle = axisInGraphColor;
            ctx.setLineDash([2, 2]);
            ctx.lineTo(xPos, 0);
            ctx.stroke();

            ctx.restore();
        }

        ctx.save();
        ctx.fillText('<- Positions Lost // Positions Gained ->', (width - axisMargin) / 2, height - axisNameMargin);
        ctx.restore();
    };

    const drawSeries = (ctx: CanvasRenderingContext2D, pixelToSeriesRatio: number, pixelToPositionRatio: number) => {
        const positionChangesCenter = axisMargin + (positionOffset) * pixelToPositionRatio;

        ctx.save();
        ctx.font = '15px Roboto';

        data.series.forEach((currentSeries: PositionChangeSeries, seriesIndex: number) => {
            const { changes, grid, finish } = currentSeries;

            const stintHeight = pixelToSeriesRatio - seriesRowSpacing;
            const rectangleUpperY = (seriesIndex + 1) * pixelToSeriesRatio - (stintHeight / 2);

            if (0 !== changes) {
                const alignFrom = 0 < changes ? 'right' : 'left';
                const alignTo = 0 < changes ? 'left' : 'right';

                const positionOffsetSign = (changes) / Math.abs(changes);

                ctx.fillStyle = currentSeries.color;

                ctx.fillRect(
                    positionChangesCenter,
                    rectangleUpperY,
                    (changes) * pixelToPositionRatio,
                    stintHeight,
                );

                ctx.fillStyle = axisColor;
                ctx.textAlign = alignFrom;
                ctx.fillText(
                    `From ${grid}`,
                    positionChangesCenter - 10 * positionOffsetSign,
                    rectangleUpperY + stintHeight / 2,
                );

                ctx.textAlign = alignTo;
                ctx.fillText(
                    `To ${finish}`,
                    positionChangesCenter + (changes) * pixelToPositionRatio + 10 * positionOffsetSign,
                    rectangleUpperY + stintHeight / 2,
                );
            } else {
                ctx.textAlign = 'center';
                ctx.fillStyle = axisColor;
                ctx.fillText(
                    `${grid} to ${finish}`,
                    positionChangesCenter + 5,
                    rectangleUpperY + stintHeight / 2,
                );
            }
        });

        ctx.restore();

            // for (let i = 0, limit = lapsPitted.length; i < limit; i += 1) {
            //     const initialLap = parseInt(lapsPitted[i], 10);
            //
            //     const hasAStintAfter = i === limit - 1;
            //     const stintAfterIsNull = null === currentSeries.tyre_history[lapsPitted[i + 1]];
            //     let finalLap = hasAStintAfter ? seriesLap : parseInt(lapsPitted[i + 1], 10);
            //
            //     if (hasAStintAfter && stintAfterIsNull) {
            //         finalLap = parseInt(lapsPitted[i + 1], 10);
            //     }
            //
            //     if (null !== currentSeries.tyre_history[lapsPitted[i]]) {
            //         const stintHeight = pixelToSeriesRatio - seriesRowSpacing;
            //         const stintStartX = axisMargin + (initialLap * pixelToLapsRatio);
            //         const rectangleUpperY = (seriesIndex + 1) * pixelToSeriesRatio - (stintHeight / 2);
            //
            //         ctx.save();
            //
            //         const tyre = currentSeries.tyre_history[lapsPitted[i]].type.toLowerCase() as tyre;
            //         ctx.fillStyle = tyresColor[tyre];
            //
            //         ctx.fillRect(
            //             stintStartX,
            //             rectangleUpperY,
            //             (finalLap - initialLap) * pixelToLapsRatio,
            //             stintHeight,
            //         );
            //
            //         if (0 !== initialLap) {
            //             ctx.fillStyle = '#000000';
            //             ctx.fillRect(
            //                 stintStartX - Math.max(stintHeight / 2, 10),
            //                 rectangleUpperY + 1,
            //                 Math.max(stintHeight, 20),
            //                 stintHeight - 2,
            //             );
            //
            //             ctx.strokeText(initialLap.toString(), stintStartX, rectangleUpperY + stintHeight / 2);
            //         }
            //
            //         ctx.stroke();
            //         ctx.restore();
            //     }
            // }
    };

    const drawPositionChangeGraph = (ctx: CanvasRenderingContext2D) => {
        ctx.textBaseline = 'middle';
        ctx.strokeStyle = axisColor;
        ctx.fillStyle = axisColor;
        ctx.textAlign = 'center';
        ctx.font = '15px Roboto';
        ctx.lineWidth = 1;

        const { width, height } = ctx.canvas;
        const pixelToPositionRatio = (width - axisMargin) / changesSpan;
        const pixelToSeriesRatio = (height - axisMargin) / seriesSpan;

        drawYAxis(ctx, pixelToSeriesRatio, width, height);
        drawXAxis(ctx, pixelToPositionRatio, width, height);

        drawSeries(ctx, pixelToSeriesRatio, pixelToPositionRatio);
    };

    return (
        <GraphContainer>
            <PositionChangeTitle type={data.session.type} />
            <Canvas draw={drawPositionChangeGraph} aspectRatio={1.7} />
        </GraphContainer>
    );
};

export default PositionChangeGraph;
