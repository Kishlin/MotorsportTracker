import firstSundayAfterEndOfMonthDate from '../firstSundayAfterEndOfMonthDate';

it('returns the date when it is a sunday', () => {
    const reference = new Date(1659225600000); // July the 31st, 2022. A Sunday.

    const actual = firstSundayAfterEndOfMonthDate(reference);

    expect(actual).toEqual(reference); // July the 31st.
});

it('returns the sunday before when it is a monday', () => {
    const reference = new Date(1667174400000); // October the 31st, 2022. A Monday.

    const actual = firstSundayAfterEndOfMonthDate(reference);

    expect(actual).toEqual(new Date(2022, 10, 6)); // November the 6th.
});

it('returns the sunday after when it is in the middle of the week', () => {
    const reference = new Date(1669766400000); // November the 30th, 2022. A Wednesday.

    const actual = firstSundayAfterEndOfMonthDate(reference);

    expect(actual).toEqual(new Date(2022, 11, 4)); // December the 4th.
});
