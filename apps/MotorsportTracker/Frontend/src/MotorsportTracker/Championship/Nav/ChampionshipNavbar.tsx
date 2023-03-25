import Select, { SelectChangeEvent } from '@mui/material/Select';
import SearchIcon from '@mui/icons-material/Search';
import FormControl from '@mui/material/FormControl';
import Typography from '@mui/material/Typography';
import MenuItem from '@mui/material/MenuItem';
import Toolbar from '@mui/material/Toolbar';
import Grid from '@mui/material/Grid';
import Icon from '@mui/material/Icon';
import React from 'react';

import championships from '../../Config/Championships';
import useNavigate from '../../../Shared/Hooks/useNavigate';

declare type ChampionshipNavbarProps = {
    championship: string,
    year: string,
    page: string,
};

const ChampionshipNavbar: React.FunctionComponent<ChampionshipNavbarProps> = ({ championship, year, page }) => {
    const { navigate, redirectionTo } = useNavigate();

    const handleYearChange = (event: SelectChangeEvent) => {
        navigate(`/${championship}/${event.target.value}/${page}`);
    };

    const handleChampionshipChange = (event: SelectChangeEvent) => {
        const targetChampionship = event.target.value;
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

    const menuProps = {
        disableScrollLock: true,
        sx: {
            maxHeight: '260px',
            '& .MuiPaper-root': {
                backgroundColor: '#5a5a5a',
            },
            '& .MuiMenuItem-root:hover': {
                backgroundColor: '#e00000',
            },
            '& .MuiMenuItem-root.Mui-selected': {
                backgroundColor: '#3f63a7',
            },
            '& .MuiMenuItem-root.Mui-selected:hover': {
                backgroundColor: '#e00000',
            },
        },
    };

    return (
        <Grid container direction="row" justifyContent="space-between">
            <Grid item flexGrow={99} sx={{ backgroundColor: '#494949' }}>
                <Toolbar disableGutters>
                    <Typography
                        onClick={redirectionTo(`/${championship}/${year}/schedule`)}
                        sx={{ mx: 2, cursor: 'pointer' }}
                        variant="h6"
                        noWrap
                    >
                        Calendar
                    </Typography>
                    <Typography
                        onClick={redirectionTo(`/${championship}/${year}/standings`)}
                        sx={{ mx: 2, cursor: 'pointer' }}
                        variant="h6"
                        noWrap
                    >
                        Standings
                    </Typography>
                    <Typography
                        onClick={redirectionTo(`/${championship}/${year}/results`)}
                        sx={{ mx: 2, cursor: 'pointer' }}
                        variant="h6"
                        noWrap
                    >
                        Results
                    </Typography>
                </Toolbar>
            </Grid>
            <Grid item flexGrow={1} sx={{ minWidth: 481, backgroundColor: '#5a5a5a', p: 1 }}>
                <Grid container direction="row" justifyContent="space-between">
                    <Icon fontSize="large" color="inherit" sx={{ mr: 2 }}>
                        <SearchIcon />
                    </Icon>
                    <FormControl variant="standard" sx={{ m: 1, minWidth: 290 }}>
                        <Select MenuProps={menuProps} value={championship} label="Championship" onChange={handleChampionshipChange}>
                            {championshipSelectItems}
                        </Select>
                    </FormControl>
                    <FormControl variant="standard" sx={{ m: 1, minWidth: 80 }}>
                        <Select MenuProps={menuProps} value={year} label="Year" onChange={handleYearChange}>
                            {yearSelectItems}
                        </Select>
                    </FormControl>
                </Grid>
            </Grid>
        </Grid>
    );
};

export default ChampionshipNavbar;
