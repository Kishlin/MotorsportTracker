import { ChampionshipList } from '../Shared/Types';

const CHAMPIONSHIPS: ChampionshipList = {
    'formula-one': {
        name: 'Formula One',
        displayName: 'Formula One',
        shortName: 'Formula 1',
        slug: 'formula-one',
        years: Array.from({ length: 74 }, (x, i) => 1950 + i),
    },
    'fia-formula-2-championship': {
        name: 'FIA Formula 2 Championship',
        displayName: 'Formula 2',
        shortName: 'Formula 2',
        slug: 'fia-formula-2-championship',
        years: Array.from({ length: 7 }, (x, i) => 2017 + i),
    },
    'fia-formula-3-championship': {
        name: 'FIA Formula 3 Championship',
        displayName: 'Formula 3',
        shortName: 'Formula 3',
        slug: 'fia-formula-3-championship',
        years: Array.from({ length: 5 }, (x, i) => 2019 + i),
    },
    'formula-e': {
        name: 'Formula E',
        displayName: 'Formula E',
        shortName: 'Formula E',
        slug: 'formula-e',
        years: [2014, 2015, 2016, 2017, 2018, 2019, 2021, 2022, 2023], // No championship in 2020
    },
    'f4-france': {
        name: 'F4 France',
        displayName: 'F4 France',
        shortName: 'F4 France',
        slug: 'f4-france',
        years: Array.from({ length: 3 }, (x, i) => 2021 + i),
    },
    'w-series': {
        name: 'W Series',
        displayName: 'W Series',
        shortName: 'W Series',
        slug: 'w-series',
        years: Array.from({ length: 3 }, (x, i) => 2019 + i),
    },
    'world-endurance-championship': {
        name: 'World Endurance Championship',
        displayName: 'World Endurance Championship',
        shortName: 'WEC',
        slug: 'world-endurance-championship',
        years: Array.from({ length: 11 }, (x, i) => 2021 + i),
    },
    'gt-world-challenge-europe': {
        name: 'GT World Challenge Europe',
        displayName: 'GT World Europe Endurance',
        shortName: 'GT Europe E',
        slug: 'gt-world-challenge-europe',
        years: Array.from({ length: 10 }, (x, i) => 2013 + i),
    },
    'gt-world-challenge-europe-sprint-cup': {
        name: 'GT World Challenge Europe Sprint Cup',
        displayName: 'GT World Europe Sprint',
        shortName: 'GT Europe S',
        slug: 'gt-world-challenge-europe-sprint-cup',
        years: Array.from({ length: 4 }, (x, i) => 2019 + i),
    },
    'gt4-france': {
        name: 'GT4 France',
        displayName: 'GT4 France',
        shortName: 'GT4 France',
        slug: 'gt4-france',
        years: Array.from({ length: 1 }, (x, i) => 2022 + i),
    },
    'imsa-sportscar-championship': {
        name: 'IMSA SportsCar Championship',
        displayName: 'IMSA SportsCar Championship',
        shortName: 'IMSA',
        slug: 'imsa-sportscar-championship',
        years: Array.from({ length: 10 }, (x, i) => 2014 + i),
    },
    'adac-gt-masters': {
        name: 'ADAC GT Masters',
        displayName: 'ADAC GT Masters',
        shortName: 'ADAC',
        slug: 'adac-gt-masters',
        years: [2021, 2022],
    },
    motogp: {
        name: 'MotoGP',
        displayName: 'MotoGP',
        shortName: 'MotoGP',
        slug: 'motogp',
        years: Array.from({ length: 75 }, (x, i) => 1949 + i),
    },
};

export default CHAMPIONSHIPS;
