import computeScheduleUri from '../computeScheduleUri';

it('computes the uri from a date', () => {
    expect(computeScheduleUri(new Date(2015, 0, 1))).toEqual('/schedule/january/2015');
    expect(computeScheduleUri(new Date(2016, 1, 1))).toEqual('/schedule/february/2016');
    expect(computeScheduleUri(new Date(2017, 2, 1))).toEqual('/schedule/march/2017');
    expect(computeScheduleUri(new Date(2018, 3, 1))).toEqual('/schedule/april/2018');
    expect(computeScheduleUri(new Date(2019, 4, 1))).toEqual('/schedule/may/2019');
    expect(computeScheduleUri(new Date(2020, 5, 1))).toEqual('/schedule/june/2020');
    expect(computeScheduleUri(new Date(2021, 6, 1))).toEqual('/schedule/july/2021');
    expect(computeScheduleUri(new Date(2022, 7, 1))).toEqual('/schedule/august/2022');
    expect(computeScheduleUri(new Date(2023, 8, 1))).toEqual('/schedule/september/2023');
    expect(computeScheduleUri(new Date(2024, 9, 1))).toEqual('/schedule/october/2024');
    expect(computeScheduleUri(new Date(2025, 10, 1))).toEqual('/schedule/november/2025');
    expect(computeScheduleUri(new Date(2026, 11, 1))).toEqual('/schedule/december/2026');
});
