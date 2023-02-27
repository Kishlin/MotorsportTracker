import React, { useState } from 'react';
import { useRouter } from 'next/router';
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

declare type MenuOpenType = {
    schedule: boolean,
    formula1: boolean,
}

const MotorsportTrackerMenu: React.FunctionComponent = () => {
    const [menuOpen, setMenuOpen] = useState<MenuOpenType>({ schedule: true, formula1: true });
    const router = useRouter();

    const toggleMenu = (key: 'schedule'|'formula1') => () => setMenuOpen({
        ...menuOpen,
        [key]: !(menuOpen[key]),
    });

    const navigate = (path: string) => () => router.replace(path);

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
                    <ListItemButton sx={{ pl: 4 }} onClick={navigate('/schedule')}>
                        <ListItemIcon>
                            <CalendarTodayIcon />
                        </ListItemIcon>
                        <ListItemText primary="Upcoming" />
                    </ListItemButton>
                </List>
                <List component="div" disablePadding>
                    <ListItemButton sx={{ pl: 4 }} onClick={navigate('/schedule/february/2022')}>
                        <ListItemIcon>
                            <CalendarMonthIcon />
                        </ListItemIcon>
                        <ListItemText primary="Monthly" />
                    </ListItemButton>
                </List>
            </Collapse>
            <Divider />
            <ListItem>
                <ListItemText primary="Standings" />
            </ListItem>
            <List component="div" disablePadding>
                <ListItem disablePadding>
                    <ListItemButton onClick={toggleMenu('formula1')}>
                        <ListItemIcon>
                            <img src="/assets/championships/logos/f1.svg" alt="f1" width={40} />
                        </ListItemIcon>
                        <ListItemText primary="Formula 1" />
                        {menuOpen.formula1 ? <ExpandLess /> : <ExpandMore />}
                    </ListItemButton>
                </ListItem>
                <Collapse in={menuOpen.formula1} timeout="auto" unmountOnExit>
                    <List component="div" disablePadding>
                        <ListItem disablePadding>
                            <ListItemButton sx={{ pl: 9 }} onClick={navigate('/standings/formula1/2022')}>
                                <ListItemText primary="2022" />
                            </ListItemButton>
                        </ListItem>
                    </List>
                </Collapse>
                <ListItem disablePadding>
                    <ListItemButton>
                        <ListItemIcon>
                            <img src="/assets/championships/logos/f2.svg" alt="f2" width={40} />
                        </ListItemIcon>
                        <ListItemText primary="Formula 2" />
                    </ListItemButton>
                </ListItem>
                <ListItem disablePadding>
                    <ListItemButton>
                        <ListItemIcon>
                            <img src="/assets/championships/logos/fe.svg" alt="fe" width={40} />
                        </ListItemIcon>
                        <ListItemText primary="Formula E" />
                    </ListItemButton>
                </ListItem>
                <ListItem disablePadding>
                    <ListItemButton>
                        <ListItemIcon>
                            <img src="/assets/championships/logos/gt-world-challenge-europe.svg" alt="gte" width={40} />
                        </ListItemIcon>
                        <ListItemText primary="GT Europe" />
                    </ListItemButton>
                </ListItem>
                <ListItem disablePadding>
                    <ListItemButton>
                        <ListItemIcon>
                            <img src="/assets/championships/logos/imsa.svg" alt="imsa" width={40} />
                        </ListItemIcon>
                        <ListItemText primary="IMSA" />
                    </ListItemButton>
                </ListItem>
                <ListItem disablePadding>
                    <ListItemButton>
                        <ListItemIcon>
                            <img src="/assets/championships/logos/motogp.svg" alt="f2" width={40} />
                        </ListItemIcon>
                        <ListItemText primary="Moto GP" />
                    </ListItemButton>
                </ListItem>
                <ListItem disablePadding>
                    <ListItemButton>
                        <ListItemIcon>
                            <img src="/assets/championships/logos/wec.svg" alt="wec" width={40} />
                        </ListItemIcon>
                        <ListItemText primary="WEC" />
                    </ListItemButton>
                </ListItem>
            </List>
        </List>
    );
};

export default MotorsportTrackerMenu;
