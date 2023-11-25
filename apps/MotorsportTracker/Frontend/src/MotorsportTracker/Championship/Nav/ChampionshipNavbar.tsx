'use client';

import { usePathname, useRouter } from 'next/navigation';
import MenuItem from '@mui/material/MenuItem';
import { styled } from '@mui/material/styles';
import { FunctionComponent } from 'react';
import Link from 'next/link';

import SelectorChampionship from '../../Shared/Nav/SelectorChampionship';
import { AvailableStandings, StandingType } from '../../Shared/Types';
import SelectorYear from '../../Shared/Nav/SelectorYear';
import NavSearchBar from '../../Shared/Nav/NavSearchBar';
import NavContainer from '../../Shared/Nav/NavContainer';
import NavMainMenu from '../../Shared/Nav/NavMainMenu';
import championships from '../../Config/Championships';

declare type ChampionshipNavbarProps = {
    availableStandings: AvailableStandings,
};

const StyledLink = styled(Link)(
    () => ({
        letterSpacing: '0.0075em',
        textDecoration: 'none',
        fontSize: '1.25rem',
        display: 'block',
        fontWeight: 500,
        lineHeight: 1.6,
        color: '#fff',
        margin: '0 16px',
    }),
);

const ChampionshipNavbar: FunctionComponent<ChampionshipNavbarProps> = ({ availableStandings }) => {
    const pathname = usePathname();
    const router = useRouter();

    const [, championship, year, page] = pathname.slice(1).split('/');

    const handleYearChange = (newYear: string) => {
        router.push(`/championship/${championship}/${newYear}/${page}`);
    };

    const handleChampionshipChange = (targetChampionship: string) => {
        const { years } = championships[targetChampionship];

        const targetYear = years.includes(parseInt(year, 10)) ? year : years[0];

        router.push(`/championship/${targetChampionship}/${targetYear}/${page}`);
    };

    const championshipSelectItems = Object.keys(championships).map((championshipSlug: string) => (
        <MenuItem key={championshipSlug} value={championshipSlug}>
            {championships[championshipSlug].displayName}
        </MenuItem>
    ));

    const yearSelectItems = championships[championship].years.slice().reverse().map((seasonYear: number) => (
        <MenuItem key={seasonYear} value={seasonYear}>{seasonYear}</MenuItem>
    ));

    const statsPage = (
            0 < Object.keys(availableStandings).length
            && (availableStandings.driver || availableStandings.constructor || availableStandings.team)
        ) ? (
            <StyledLink href={`/championship/${championship}/${year}/stats`}>
                Stats
            </StyledLink>
        )
        : <noscript />;

    // noinspection PointlessBooleanExpressionJS
    const standingsPages = ['constructor', 'team', 'driver']
        // standingPages.constructor exists because it's an object. Strong type checking is needed.
        .filter((type: StandingType) => true === availableStandings[type])
        .map((type: StandingType) => (
            <StyledLink key={type} href={`/championship/${championship}/${year}/standings-${type}`}>
                {`${type.slice(0, 1).toUpperCase()}${type.slice(1)} Standings`}
            </StyledLink>
        ));

    return (
        <NavContainer>
            <NavMainMenu>
                <StyledLink href={`/championship/${championship}/${year}/schedule`}>
                    Calendar
                </StyledLink>
                {statsPage}
                {standingsPages}
            </NavMainMenu>
            <NavSearchBar>
                <SelectorChampionship onChange={handleChampionshipChange} championship={championship}>
                    {championshipSelectItems}
                </SelectorChampionship>
                <SelectorYear onChange={handleYearChange} year={year}>
                    {yearSelectItems}
                </SelectorYear>
            </NavSearchBar>
        </NavContainer>
    );
};

export default ChampionshipNavbar;
