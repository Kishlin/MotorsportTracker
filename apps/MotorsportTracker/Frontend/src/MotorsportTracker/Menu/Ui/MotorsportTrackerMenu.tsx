'use client';

import React, { useState } from 'react';
import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemButton from '@mui/material/ListItemButton';
import ListItemIcon from '@mui/material/ListItemIcon';
import CalendarTodayIcon from '@mui/icons-material/CalendarToday';
import CalendarMonthIcon from '@mui/icons-material/CalendarMonth';
import ListItemText from '@mui/material/ListItemText';
import Divider from '@mui/material/Divider';
import ExpandLess from '@mui/icons-material/ExpandLess';
import ExpandMore from '@mui/icons-material/ExpandMore';
import Collapse from '@mui/material/Collapse';

import championships from '../../Config/Championships';
import useNavigate from '../../../Shared/Hooks/useNavigate';

declare type MenuOpenType = {
    schedule: boolean,
}

const MotorsportTrackerMenu: React.FunctionComponent = () => {
    const [menuOpen, setMenuOpen] = useState<MenuOpenType>({ schedule: true });

    const toggleMenu = (key: 'schedule') => () => setMenuOpen({
        ...menuOpen,
        [key]: !(menuOpen[key]),
    });

    const { redirectionTo } = useNavigate();

    const now = new Date();
    const schedulePath = now.toLocaleString('en-US', { month: 'long', year: 'numeric' }).toLowerCase().replace(' ', '/');

    const championshipsMenu = Object.keys(championships).map((championshipSlug: string) => {
        const year = championships[championshipSlug].years[championships[championshipSlug].years.length - 1];

        return (
            <ListItem key={championshipSlug} disablePadding>
                <ListItemButton onClick={redirectionTo(`/championship/${championshipSlug}/${year}/schedule`)}>
                    <ListItemIcon>
                        <img src={`/assets/championships/logos/${championshipSlug}.svg`} alt={championshipSlug} height={24} />
                    </ListItemIcon>
                    <ListItemText sx={{ ml: 1 }} primary={championships[championshipSlug].shortName} />
                </ListItemButton>
            </ListItem>
        );
    });

    return (
        <List>
            <ListItem disablePadding>
                <ListItemButton onClick={toggleMenu('schedule')}>
                    <ListItemIcon>
                        <CalendarMonthIcon />
                    </ListItemIcon>
                    <ListItemText primary="Schedule" />
                    {menuOpen.schedule ? <ExpandLess /> : <ExpandMore />}
                </ListItemButton>
            </ListItem>
            <Collapse in={menuOpen.schedule} timeout="auto" unmountOnExit>
                <List component="div" disablePadding>
                    <ListItemButton sx={{ pl: 4 }} onClick={redirectionTo('/')}>
                        <ListItemIcon>
                            <CalendarTodayIcon />
                        </ListItemIcon>
                        <ListItemText primary="Upcoming" />
                    </ListItemButton>
                </List>
                <List component="div" disablePadding>
                    <ListItemButton sx={{ pl: 4 }} onClick={redirectionTo(`/schedule/${schedulePath}`)}>
                        <ListItemIcon>
                            <CalendarMonthIcon />
                        </ListItemIcon>
                        <ListItemText primary="Monthly" />
                    </ListItemButton>
                </List>
            </Collapse>
            <Divider />
            {championshipsMenu}
        </List>
    );
};

export default MotorsportTrackerMenu;
