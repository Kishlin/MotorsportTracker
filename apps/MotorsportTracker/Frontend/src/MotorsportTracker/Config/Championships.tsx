import { ChampionshipList } from '../Shared/Types';

const CHAMPIONSHIPS: ChampionshipList = {
    'formula-one': {
        name: 'Formula One',
        shortName: 'Formula 1',
        slug: 'formula-one',
        years: Array.from({ length: 74 }, (x, i) => 1950 + i),
    },
    'formula-2': {
        name: 'FIA Formula 2 Championship',
        shortName: 'Formula 2',
        slug: 'formula-2',
        years: Array.from({ length: 7 }, (x, i) => 2017 + i),
    },
    'formula-3': {
        name: 'FIA Formula 3 Championship',
        shortName: 'Formula 3',
        slug: 'formula-3',
        years: Array.from({ length: 5 }, (x, i) => 2019 + i),
    },
    'f4-france': {
        name: 'F4 France',
        shortName: 'F4 France',
        slug: 'f4-france',
        years: Array.from({ length: 3 }, (x, i) => 2021 + i),
    },
    'formula-e': {
        name: 'Formula E',
        shortName: 'Formula E',
        slug: 'formula-e',
        years: [2014, 2015, 2016, 2017, 2018, 2019, 2021, 2022, 2023], // No championship in 2020
    },
    'w-series': {
        name: 'W Series',
        shortName: 'W Series',
        slug: 'w-series',
        years: Array.from({ length: 3 }, (x, i) => 2019 + i),
    },
    'world-endurance-championship': {
        name: 'World Endurance Championship',
        shortName: 'WEC',
        slug: 'world-endurance-championship',
        years: Array.from({ length: 11 }, (x, i) => 2021 + i),
    },
    'gt-world-challenge-europe': {
        name: 'GT World Challenge Europe',
        shortName: 'GT Europe E',
        slug: 'gt-world-challenge-europe',
        years: Array.from({ length: 10 }, (x, i) => 2013 + i),
    },
    'gt-world-challenge-europe-sprint-cup': {
        name: 'GT World Challenge Europe Sprint Cup',
        shortName: 'GT Europe S',
        slug: 'gt-world-challenge-europe-sprint-cup',
        years: Array.from({ length: 4 }, (x, i) => 2019 + i),
    },
    'gt4-france': {
        name: 'GT4 France',
        shortName: 'GT4 France',
        slug: 'gt4-france',
        years: Array.from({ length: 1 }, (x, i) => 2022 + i),
    },
    'imsa-sportscar-championship': {
        name: 'IMSA SportsCar Championship',
        shortName: 'IMSA',
        slug: 'imsa-sportscar-championship',
        years: Array.from({ length: 10 }, (x, i) => 2014 + i),
    },
    'adac-gt-masters': {
        name: 'ADAC GT Masters',
        shortName: 'ADAC',
        slug: 'adac-gt-masters',
        years: [2021, 2022],
    },
    motogp: {
        name: 'MotoGP',
        shortName: 'MotoGP',
        slug: 'motogp',
        years: Array.from({ length: 75 }, (x, i) => 1949 + i),
    },
};

export default CHAMPIONSHIPS;
