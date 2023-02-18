export type MotorsportEvent = {
    championship_slug: string,
    color: string,
    date_time: string,
    icon: string
    name: string,
    reference: string,
    type: string,
    venue_label: string,
};

export type EventCalendarDay = {
    [key: `${string}-${string}-${string} ${string}:${string}:${string}`]: MotorsportEvent,
};

export type EventCalendarMonth = {
    [key: `${string}-${string}-${string}`]: EventCalendarDay
};
