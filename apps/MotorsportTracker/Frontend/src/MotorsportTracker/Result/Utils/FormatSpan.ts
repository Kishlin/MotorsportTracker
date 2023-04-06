import { ResultClassification } from '../Types/Index';
import formatTime from './FormatTime';

declare type SpanFormatter = (classification: ResultClassification, time: string, laps: number) => string;

const formatSpan: SpanFormatter = (classification, time, laps) => {
    if ('CLA' !== classification) {
        return '';
    }

    if (0 !== laps) {
        return `${laps} Laps`;
    }

    if ('0' !== time) {
        return `+ ${formatTime(time)}`;
    }

    return '';
};

export default formatSpan;
