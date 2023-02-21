const multiplier = 86400000; // 24 * 60 * 60 * 1000

function previousMonday(reference: Date): Date {
    // if (0 === reference.getDay()) { // Day is a Sunday
    //     return new Date(reference.getTime());
    // }

    return new Date(reference.getTime() - (((reference.getDay() + 7 - 1) % 7) * multiplier));
}

export default previousMonday;
