'use client';

import { FunctionComponent, ReactNode } from 'react';
import FormControl from '@mui/material/FormControl';

import Selector from '../../../Shared/Nav/Selector';

declare type TypeSelectorProps = {
    onChange: (value: string) => void,
    children: ReactNode,
    selected: string,
};

const TypeSelector: FunctionComponent<TypeSelectorProps> = ({ onChange, selected, children }) => (
    <FormControl variant="standard" sx={{ m: 1, maxWidth: 250 }}>
        <Selector onChange={onChange} value={selected}>
            {children}
        </Selector>
    </FormControl>
);

export default TypeSelector;
