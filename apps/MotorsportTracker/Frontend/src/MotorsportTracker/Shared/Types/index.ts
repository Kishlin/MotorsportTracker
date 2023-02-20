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

export type DailyEventsSchedule = {
    [key: `${string}-${string}-${string} ${string}:${string}:${string}`]: MotorsportEvent,
};

export type EventsSchedule = {
    [key: `${string}-${string}-${string}`]: DailyEventsSchedule
};
