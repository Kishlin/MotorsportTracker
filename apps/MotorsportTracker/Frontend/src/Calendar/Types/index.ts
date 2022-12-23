export type MotorsportEvent = {
    championship: string,
    date_time: string,
    event: string,
    venue: string,
    type: string,
};

export type EventCalendarDay = {
    [key: `${string}-${string}-${string} ${string}:${string}:${string}`]: MotorsportEvent,
};

export type EventCalendarMonth = {
    [key: `${string}-${string}-${string}`]: EventCalendarDay
};
