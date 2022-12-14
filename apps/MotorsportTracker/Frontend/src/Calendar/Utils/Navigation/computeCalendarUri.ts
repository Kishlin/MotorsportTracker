declare type ComputeCalendarUri = (date: Date) => string;

const computeCalendarUri: ComputeCalendarUri = (date) => {
    const month = date.toLocaleString(undefined, { month: 'long' }).toLowerCase();
    const year = date.toLocaleString(undefined, { year: 'numeric' });

    return `/calendar/${month}/${year}`;
};

export default computeCalendarUri;
