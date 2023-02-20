const multiplier = 86400000; // 24 * 60 * 60 * 1000

function firstSundayAfterEndOfMonthDate(reference: Date): Date {
    const lastDayOfTheMonth = new Date(
        reference.getFullYear(),
        reference.getMonth() + 1,
        0,
    );

    if (0 === lastDayOfTheMonth.getDay()) { // Day is a Sunday
        return new Date(lastDayOfTheMonth.getTime());
    }

    return new Date(lastDayOfTheMonth.getTime() + (7 - lastDayOfTheMonth.getDay()) * multiplier);
}

export default firstSundayAfterEndOfMonthDate;
