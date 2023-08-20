'use client';

import { FunctionComponent, useEffect, useState } from 'react';
import MenuItem from '@mui/material/MenuItem';
import Box from '@mui/material/Box';

import CategorySelector from './CategorySelector/CategorySelector';
import StandingsTableStanding from './Table/StandingsTableStanding';
import { Standing, StandingType } from '../../Shared/Types';
import StandingsTableHead from './Table/StandingsTableHead';
import StandingsTableBody from './Table/StandingsTableBody';
import StandingsTable from './Table/StandingsTable';
import { List } from '../../../Shared/Types/Index';

declare type StandingsContentProps = {
    standings: List<Array<Standing>>,
    type: StandingType,
}

const StandingsContent: FunctionComponent<StandingsContentProps> = ({ standings, type }) => {
    const categories = Object.keys(standings);

    const [selectedCategory, setSelectedCategory] = useState<string>(categories[0]);

    useEffect(
        () => {
            setSelectedCategory(categories[0]);
        },
        [standings],
    );

    const handleCategorySelectorChange = (targetCategory: string) => {
        setSelectedCategory(targetCategory);
    };

    const categorySelectorItems = categories.map((category: string) => (
        <MenuItem key={category} value={category}>
            {category}
        </MenuItem>
    ));

    let classSelector = (<noscript />);
    if (1 < categories.length) {
        classSelector = (
            <CategorySelector onChange={handleCategorySelectorChange} selected={selectedCategory}>
                {categorySelectorItems}
            </CategorySelector>
        );
    }

    return (
        <>
            {classSelector}
            <Box sx={{ maxWidth: '500px' }}>
                <StandingsTable>
                    <StandingsTableHead type={type} />
                    <StandingsTableBody>
                        {standings[selectedCategory].map(
                            (standing: Standing) => (
                                <StandingsTableStanding key={standing.id} standing={standing} />
                            ),
                        )}
                    </StandingsTableBody>
                </StandingsTable>
            </Box>
        </>
    );
};

export default StandingsContent;
