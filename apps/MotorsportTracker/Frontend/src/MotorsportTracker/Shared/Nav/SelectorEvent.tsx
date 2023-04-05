import FormControl from '@mui/material/FormControl';
import React from 'react';

import Selector from './Selector';

declare type SelectorChampionshipProps = {
    onChange: (value: string) => void,
    children: React.ReactNode,
    event: string,
}

const SelectorChampionship: React.FunctionComponent<SelectorChampionshipProps> = ({
    onChange,
    children,
    event,
}) => (
    <FormControl variant="standard" sx={{ m: 1, minWidth: 290 }}>
        <Selector onChange={onChange} value={event}>
            {children}
        </Selector>
    </FormControl>
);

export default SelectorChampionship;
