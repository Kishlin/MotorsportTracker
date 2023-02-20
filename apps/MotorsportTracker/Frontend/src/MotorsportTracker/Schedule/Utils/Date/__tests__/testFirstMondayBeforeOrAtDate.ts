import firstMondayBeforeOrAtDate from '../firstMondayBeforeOrAtDate';

it('returns the date when it is a monday', () => {
    const reference = new Date(1659312000000); // August the 1st, 2022. A Monday.

    const actual = firstMondayBeforeOrAtDate(reference);

    expect(actual).toEqual(reference);
});

it('returns the monday before when it is a sunday', () => {
    const reference = new Date(1651363200000); // May the 1st, 2022. A Sunday.

    const actual = firstMondayBeforeOrAtDate(reference);

    expect(actual).toEqual(new Date(2022, 3, 25));
});

it('returns the monday before when it is in the middle of the week', () => {
    const reference = new Date(1661990400000); // September the 1st, 2022. A Thursday.

    const actual = firstMondayBeforeOrAtDate(reference);

    expect(actual).toEqual(new Date(2022, 7, 29));
});
