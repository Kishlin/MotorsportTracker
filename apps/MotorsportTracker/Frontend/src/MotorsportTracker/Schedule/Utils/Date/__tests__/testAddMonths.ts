import addMonths from '../addMonths';

it('adds one month to the first monthday', () => {
    const reference = new Date(2022, 7, 1); // August the 1st, 2022

    expect(addMonths(reference, 1)).toEqual(new Date(2022, 8, 1)); // September the 1st, 2022
});

it('removes one month the first ', () => {
    const reference = new Date(2022, 7, 1); // August the 1st, 2022

    expect(addMonths(reference, -1)).toEqual(new Date(2022, 6, 1)); // July the 1st, 2022
});

it('handles shorter months correctly', () => {
    const reference = new Date(2022, 4, 31); // May the 31st, 2022

    expect(addMonths(reference, 1)).toEqual(new Date(2022, 5, 30)); // June the 30th, 2022

    expect(addMonths(reference, -1)).toEqual(new Date(2022, 3, 30)); // April the 30th, 2022
});

it('handles February correctly', () => {
    const lastDayOfJanuary = new Date(2022, 0, 31); // January the 31st, 2022
    expect(addMonths(lastDayOfJanuary, 1)).toEqual(new Date(2022, 1, 28)); // February the 28th, 2022

    const lastDayOfMarch = new Date(2022, 2, 31); // March the 31st, 2022
    expect(addMonths(lastDayOfMarch, -1)).toEqual(new Date(2022, 1, 28)); // February the 28th, 2022
});

it('can add or subtracts multiple months', () => {
    const reference = new Date(2022, 7, 1); // August the 1st, 2022

    expect(addMonths(reference, 2)).toEqual(new Date(2022, 9, 1)); // October the 1st, 2023
    expect(addMonths(reference, 5)).toEqual(new Date(2023, 0, 1)); // January the 1st, 2023
    expect(addMonths(reference, 12)).toEqual(new Date(2023, 7, 1)); // August the 1st, 2023
    expect(addMonths(reference, 42)).toEqual(new Date(2026, 1, 1)); // February the 1st, 2026

    expect(addMonths(reference, -3)).toEqual(new Date(2022, 4, 1)); // May the 1st, 2021
    expect(addMonths(reference, -10)).toEqual(new Date(2021, 9, 1)); // October the 1st, 2021
    expect(addMonths(reference, -12)).toEqual(new Date(2021, 7, 1)); // August the 1st, 2021
    expect(addMonths(reference, -69)).toEqual(new Date(2016, 10, 1)); // November the 1st, 2016
});
