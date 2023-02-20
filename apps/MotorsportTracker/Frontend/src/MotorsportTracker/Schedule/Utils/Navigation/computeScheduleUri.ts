declare type ComputeScheduleUri = (date: Date) => string;

const computeScheduleUri: ComputeScheduleUri = (date) => {
    const month = date.toLocaleString(undefined, { month: 'long' }).toLowerCase();
    const year = date.toLocaleString(undefined, { year: 'numeric' });

    return `/schedule/${month}/${year}`;
};

export default computeScheduleUri;
