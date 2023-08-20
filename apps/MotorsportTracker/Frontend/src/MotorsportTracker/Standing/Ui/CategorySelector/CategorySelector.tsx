'use client';

import { FunctionComponent, ReactNode } from 'react';
import FormControl from '@mui/material/FormControl';

import Selector from '../../../Shared/Nav/Selector';

declare type CategorySelectorProps = {
    onChange: (value: string) => void,
    children: ReactNode,
    selected: string,
};

const CategorySelector: FunctionComponent<CategorySelectorProps> = ({ onChange, selected, children }) => (
    <FormControl variant="standard" sx={{ m: 1, maxWidth: 150 }}>
        <Selector onChange={onChange} value={selected}>
            {children}
        </Selector>
    </FormControl>
);

export default CategorySelector;
