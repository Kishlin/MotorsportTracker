function formatDateForCalendar(date: Date): string {
    if (1 === date.getDate()) {
        return date.toLocaleString(undefined, { month: 'short', day: 'numeric' });
    }

    return date.toLocaleString(undefined, { day: 'numeric' });
}

export default formatDateForCalendar;
