declare type FormatDateAsScheduleKey = (date: Date) => `${string}-${string}-${string}`;

const formatDateAsScheduleKey: FormatDateAsScheduleKey = (date) => {
    const year = date.getUTCFullYear();
    const month = (`00${date.getUTCMonth() + 1}`).slice(-2);
    const day = (`00${date.getUTCDate() + 1}`).slice(-2);

    return `${year}-${month}-${day}`;
};

export default formatDateAsScheduleKey;
