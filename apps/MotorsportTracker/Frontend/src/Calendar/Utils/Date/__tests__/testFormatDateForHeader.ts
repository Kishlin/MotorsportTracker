import formatDateForHeader from '../formatDateForHeader';

it('formats the date as expected', () => {
    const reference = new Date(2022, 7, 1); // August the 1st, 2022

    expect(formatDateForHeader(reference)).toEqual('August 2022');
});
