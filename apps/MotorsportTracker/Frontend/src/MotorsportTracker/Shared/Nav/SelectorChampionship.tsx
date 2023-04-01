import FormControl from '@mui/material/FormControl';
import React from 'react';

import Selector from './Selector';

declare type SelectorChampionshipProps = {
    onChange: (value: string) => void,
    children: React.ReactNode,
    championship: string,
}

const SelectorChampionship: React.FunctionComponent<SelectorChampionshipProps> = ({
    championship,
    onChange,
    children,
}) => (
    <FormControl variant="standard" sx={{ m: 1, minWidth: 290 }}>
        <Selector onChange={onChange} value={championship}>
            {children}
        </Selector>
    </FormControl>
);

export default SelectorChampionship;
