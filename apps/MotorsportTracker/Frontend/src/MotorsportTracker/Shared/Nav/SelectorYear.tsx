import FormControl from '@mui/material/FormControl';
import React from 'react';

import Selector from './Selector';

declare type SelectorYearProps = {
    onChange: (value: string) => void,
    children: React.ReactNode,
    year: string,
}

const SelectorYear: React.FunctionComponent<SelectorYearProps> = ({
    onChange,
    children,
    year,
}) => (
    <FormControl variant="standard" sx={{ m: 1, minWidth: 80 }}>
        <Selector onChange={onChange} value={year}>
            {children}
        </Selector>
    </FormControl>
);

export default SelectorYear;
