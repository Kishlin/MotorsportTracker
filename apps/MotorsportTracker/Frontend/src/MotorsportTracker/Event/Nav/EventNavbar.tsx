'use client';

import { usePathname, useRouter } from 'next/navigation';
import MenuItem from '@mui/material/MenuItem';
import { styled } from '@mui/material/styles';
import { FunctionComponent } from 'react';
import Link from 'next/link';

import SelectorChampionship from '../../Shared/Nav/SelectorChampionship';
import SelectorEvent from '../../Shared/Nav/SelectorEvent';
import SelectorYear from '../../Shared/Nav/SelectorYear';
import NavSearchBar from '../../Shared/Nav/NavSearchBar';
import NavContainer from '../../Shared/Nav/NavContainer';
import NavMainMenu from '../../Shared/Nav/NavMainMenu';
import championships from '../../Config/Championships';
import { SeasonEvents } from '../../Shared/Types';

declare type EventNavbarProps = {
    season: SeasonEvents,
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

const EventNavbar: FunctionComponent<EventNavbarProps> = ({
    season,
}) => {
    const pathname = usePathname();
    const router = useRouter();

    const [, championship, year, event, page] = pathname.slice(1).split('/');

    const handleChampionshipChange = (targetChampionship: string) => {
        const { years } = championships[targetChampionship];

        const targetYear = years.includes(parseInt(year, 10)) ? year : years[0];

        router.push(`/championship/${targetChampionship}/${targetYear}/schedule`);
    };

    const handleYearChange = (newYear: string) => {
        router.push(`/championship/${championship}/${newYear}/schedule`);
    };

    const handleEventChange = (newEvent: string) => {
        router.push(`/event/${championship}/${year}/${newEvent}/${page}`);
    };

    const championshipSelectItems = Object.keys(championships).map((championshipSlug: string) => (
        <MenuItem key={championshipSlug} value={championshipSlug}>
            {championships[championshipSlug].displayName}
        </MenuItem>
    ));

    const yearSelectItems = championships[championship].years.slice().reverse().map((seasonYear: number) => (
        <MenuItem key={seasonYear} value={seasonYear}>{seasonYear}</MenuItem>
    ));

    const eventSelectItems = Object.keys(season).map((key: string) => (
        <MenuItem key={key} value={season[key].slug}>{season[key].name}</MenuItem>
    ));

    return (
        <NavContainer>
            <NavMainMenu>
                <StyledLink href={`/event/${championship}/${year}/${event}/results`}>
                    Results
                </StyledLink>
                <StyledLink href={`/event/${championship}/${year}/${event}/histories`}>
                    Histories
                </StyledLink>
                <StyledLink href={`/event/${championship}/${year}/${event}/graphs`}>
                    Graphs
                </StyledLink>
            </NavMainMenu>
            <NavSearchBar>
                <SelectorChampionship onChange={handleChampionshipChange} championship={championship}>
                    {championshipSelectItems}
                </SelectorChampionship>
                <SelectorYear onChange={handleYearChange} year={year}>
                    {yearSelectItems}
                </SelectorYear>
                <SelectorEvent onChange={handleEventChange} event={event}>
                    {eventSelectItems}
                </SelectorEvent>
            </NavSearchBar>
        </NavContainer>
    );
};

export default EventNavbar;
