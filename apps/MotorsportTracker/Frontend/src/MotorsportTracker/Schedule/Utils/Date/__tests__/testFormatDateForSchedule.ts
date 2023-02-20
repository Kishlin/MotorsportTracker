import formatDateForSchedule from '../formatDateForSchedule';

it('Displays the month when it is the first day of the month', () => {
    const reference = new Date(2022, 7, 1); // August the 1st, 2022

    const actual = formatDateForSchedule(reference);

    expect(actual).toEqual('Aug 1');
});

it('does not display a trailing 0', () => {
    const reference = new Date(2022, 7, 5); // August the 5th, 2022

    const actual = formatDateForSchedule(reference);

    expect(actual).toEqual('5');
});

it('displays the monthday as a numeric', () => {
    const reference = new Date(2022, 7, 21); // August the 21st, 2022.

    const actual = formatDateForSchedule(reference);

    expect(actual).toEqual('21');
});
