import MenuItem from '@mui/material/MenuItem';
import React from 'react';

import SelectorChampionship from '../../Shared/Nav/SelectorChampionship';
import useNavigate from '../../../Shared/Hooks/useNavigate';
import SelectorEvent from '../../Shared/Nav/SelectorEvent';
import SelectorYear from '../../Shared/Nav/SelectorYear';
import NavSearchBar from '../../Shared/Nav/NavSearchBar';
import NavContainer from '../../Shared/Nav/NavContainer';
import NavMainMenu from '../../Shared/Nav/NavMainMenu';
import championships from '../../Config/Championships';
import { SeasonEvents } from '../../Shared/Types';
import Link from '../../Shared/Nav/Link';

declare type EventNavbarProps = {
    championship: string,
    year: string,
    event: string,
    season: SeasonEvents,
    page: string,
};

const EventNavbar: React.FunctionComponent<EventNavbarProps> = ({
    championship,
    year,
    event,
    season,
    page,
}) => {
    const { navigate } = useNavigate();

    const handleChampionshipChange = (targetChampionship: string) => {
        const { years } = championships[targetChampionship];

        const targetYear = years.includes(parseInt(year, 10)) ? year : years[0];

        navigate(`/${targetChampionship}/${targetYear}/schedule`);
    };

    const handleYearChange = (newYear: string) => {
        navigate(`/${championship}/${newYear}/schedule`);
    };

    const handleEventChange = (newEvent: string) => {
        navigate(`/${championship}/${year}/${newEvent}/${page}`);
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
                <Link to={`/${championship}/${year}/${event}/results`}>
                    Results
                </Link>
                <Link to={`/${championship}/${year}/${event}/histories`}>
                    Histories
                </Link>
                <Link to={`/${championship}/${year}/${event}/graphs`}>
                    Graphs
                </Link>
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
