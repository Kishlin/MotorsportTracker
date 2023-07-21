import MenuItem from '@mui/material/MenuItem';
import React from 'react';

import SelectorChampionship from '../../Shared/Nav/SelectorChampionship';
import useNavigate from '../../../Shared/Hooks/useNavigate';
import SelectorYear from '../../Shared/Nav/SelectorYear';
import NavSearchBar from '../../Shared/Nav/NavSearchBar';
import NavContainer from '../../Shared/Nav/NavContainer';
import NavMainMenu from '../../Shared/Nav/NavMainMenu';
import championships from '../../Config/Championships';
import Link from '../../Shared/Nav/Link';

declare type ChampionshipNavbarProps = {
    championship: string,
    year: string,
    page: string,
};

const ChampionshipNavbar: React.FunctionComponent<ChampionshipNavbarProps> = ({ championship, year, page }) => {
    const { navigate } = useNavigate();

    const handleYearChange = (newYear: string) => {
        navigate(`/championship/${championship}/${newYear}/${page}`);
    };

    const handleChampionshipChange = (targetChampionship: string) => {
        const { years } = championships[targetChampionship];

        const targetYear = years.includes(parseInt(year, 10)) ? year : years[0];

        navigate(`/${targetChampionship}/${targetYear}/${page}`);
    };

    const championshipSelectItems = Object.keys(championships).map((championshipSlug: string) => (
        <MenuItem key={championshipSlug} value={championshipSlug}>
            {championships[championshipSlug].displayName}
        </MenuItem>
    ));

    const yearSelectItems = championships[championship].years.slice().reverse().map((seasonYear: number) => (
        <MenuItem key={seasonYear} value={seasonYear}>{seasonYear}</MenuItem>
    ));

    return (
        <NavContainer>
            <NavMainMenu>
                <Link to={`/championship/${championship}/${year}/schedule`}>
                    Calendar
                </Link>
                <Link to={`/championship/${championship}/${year}/standings-driver`}>
                    Driver Standings
                </Link>
                <Link to={`/championship/${championship}/${year}/standings-team`}>
                    Team Standings
                </Link>
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
