import React from 'react';

import { TyreHistoryGraphData, TyreHistorySeries } from '../../Shared/Types';
import GraphContainer from '../../Shared/Ui/GraphContainer';
import TyreHistoryTitle from './TyreHistoryTitle';
import Canvas from '../../../Canvas/Ui/Canvas';

declare type TyreHistoryGraphProps = {
    data: TyreHistoryGraphData,
    isMultiDriver: boolean,
};

const axisColor = '#ffffff';

const axisMargin = 50;
const tickStart = 40;
const labelsMargin = 25;
const axisNameMargin = 10;
const seriesRowSpacing = 8;
const marginLaps = 5;

declare type tyre = 'h' | 'm' | 's' | 'i' | 'w';

declare type TyresColors = {
    [key in tyre]: string;
};

const tyresColor: TyresColors = {
    h: '#eeedee',
    m: '#f6bd00',
    s: '#e90127',
    i: '#00f217',
    w: '#00aef1',
};

const TyreHistoryGraph: React.FunctionComponent<TyreHistoryGraphProps> = ({ data, isMultiDriver }) => {
    const { laps } = data;

    const seriesCount = data.series.length;
    const seriesSpan = seriesCount + 1;
    const lapsTotal = laps + marginLaps;

    const drawYAxis = (ctx: CanvasRenderingContext2D, pixelToSeriesRatio: number, width: number, height: number) => {
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(axisMargin, 0);
        ctx.lineTo(axisMargin, height - axisMargin);
        ctx.stroke();
        ctx.restore();

        ctx.save();

        data.series.forEach((series: TyreHistorySeries, index: number) => {
            ctx.fillStyle = series.color;
            ctx.fillText(
                isMultiDriver ? series.car_number.toString() : series.short_code.slice(0, 3),
                labelsMargin,
                (index + 1) * pixelToSeriesRatio,
            );
        });

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

    const drawSeries = (ctx: CanvasRenderingContext2D, pixelToSeriesRatio: number, pixelToLapsRatio: number) => {
        data.series.forEach((currentSeries: TyreHistorySeries, seriesIndex: number) => {
            const lapsPitted = Object.keys(currentSeries.tyre_history);
            const seriesLap = currentSeries.laps;

            ctx.fillStyle = currentSeries.color;

            for (let i = 0, limit = lapsPitted.length; i < limit; i += 1) {
                const initialLap = parseInt(lapsPitted[i], 10);

                const hasAStintAfter = i === limit - 1;
                const stintAfterIsNull = null === currentSeries.tyre_history[lapsPitted[i + 1]];
                let finalLap = hasAStintAfter ? seriesLap : parseInt(lapsPitted[i + 1], 10);

                if (hasAStintAfter && stintAfterIsNull) {
                    finalLap = parseInt(lapsPitted[i + 1], 10);
                }

                if (null !== currentSeries.tyre_history[lapsPitted[i]]) {
                    const stintHeight = pixelToSeriesRatio - seriesRowSpacing;
                    const stintStartX = axisMargin + (initialLap * pixelToLapsRatio);
                    const rectangleUpperY = (seriesIndex + 1) * pixelToSeriesRatio - (stintHeight / 2);

                    ctx.save();

                    const tyre = currentSeries.tyre_history[lapsPitted[i]].type.toLowerCase() as tyre;
                    ctx.fillStyle = tyresColor[tyre];

                    ctx.fillRect(
                        stintStartX,
                        rectangleUpperY,
                        (finalLap - initialLap) * pixelToLapsRatio,
                        stintHeight,
                    );

                    if (0 !== initialLap) {
                        ctx.fillStyle = '#000000';
                        ctx.fillRect(
                            stintStartX - Math.max(stintHeight / 2, 10),
                            rectangleUpperY + 1,
                            Math.max(stintHeight, 20),
                            stintHeight - 2,
                        );

                        ctx.strokeText(initialLap.toString(), stintStartX, rectangleUpperY + stintHeight / 2);
                    }

                    ctx.stroke();
                    ctx.restore();
                }
            }
        });
    };

    const drawTyreHistoryGraph = (ctx: CanvasRenderingContext2D) => {
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';
        ctx.strokeStyle = axisColor;
        ctx.fillStyle = axisColor;
        ctx.lineWidth = 1;
        ctx.font = '15px Roboto';

        const { width, height } = ctx.canvas;
        const pixelToLapsRatio = (width - axisMargin) / lapsTotal;
        const pixelToSeriesRatio = (height - axisMargin) / seriesSpan;

        drawYAxis(ctx, pixelToSeriesRatio, width, height);
        drawXAxis(ctx, pixelToLapsRatio, width, height);

        drawSeries(ctx, pixelToSeriesRatio, pixelToLapsRatio);
    };

    return (
        <GraphContainer>
            <TyreHistoryTitle type={data.session.type} />
            <Canvas draw={drawTyreHistoryGraph} aspectRatio={1.7} />
        </GraphContainer>
    );
};

export default TyreHistoryGraph;
