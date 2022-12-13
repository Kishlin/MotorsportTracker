const offsetForSunday = 518400000; // 6 * 24 * 60 * 60 * 1000
const multiplier = 86400000; // 24 * 60 * 60 * 1000

function firstMondayBeforeOrAtDate(reference: Date): Date {
    const referenceDay = reference.getDay();

    if (0 === referenceDay) { // Day is a Sunday
        return new Date(reference.getTime() - offsetForSunday);
    }

    return new Date(reference.getTime() - (referenceDay - 1) * multiplier);
}

export default firstMondayBeforeOrAtDate;
