declare type FormatDateForHeader = (date: Date) => string;

const options: Intl.DateTimeFormatOptions = { month: 'long', year: 'numeric' };

const formatDateForHeader: FormatDateForHeader = (date) => date.toLocaleString(undefined, options);

export default formatDateForHeader;
