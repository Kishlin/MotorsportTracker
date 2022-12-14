import computeCalendarUri from '../computeCalendarUri';

it('computes the uri from a date', () => {
    expect(computeCalendarUri(new Date(2015, 0, 1))).toEqual('/calendar/january/2015');
    expect(computeCalendarUri(new Date(2016, 1, 1))).toEqual('/calendar/february/2016');
    expect(computeCalendarUri(new Date(2017, 2, 1))).toEqual('/calendar/march/2017');
    expect(computeCalendarUri(new Date(2018, 3, 1))).toEqual('/calendar/april/2018');
    expect(computeCalendarUri(new Date(2019, 4, 1))).toEqual('/calendar/may/2019');
    expect(computeCalendarUri(new Date(2020, 5, 1))).toEqual('/calendar/june/2020');
    expect(computeCalendarUri(new Date(2021, 6, 1))).toEqual('/calendar/july/2021');
    expect(computeCalendarUri(new Date(2022, 7, 1))).toEqual('/calendar/august/2022');
    expect(computeCalendarUri(new Date(2023, 8, 1))).toEqual('/calendar/september/2023');
    expect(computeCalendarUri(new Date(2024, 9, 1))).toEqual('/calendar/october/2024');
    expect(computeCalendarUri(new Date(2025, 10, 1))).toEqual('/calendar/november/2025');
    expect(computeCalendarUri(new Date(2026, 11, 1))).toEqual('/calendar/december/2026');
});
