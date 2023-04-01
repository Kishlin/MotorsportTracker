import Select, { SelectChangeEvent } from '@mui/material/Select';
import React from 'react';

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

declare type SelectorProps = {
    onChange: (value: string) => void,
    value: string,
    children: React.ReactNode,
};

const Selector: React.FunctionComponent<SelectorProps> = ({ onChange, value, children }) => {
    const handleChange = (event: SelectChangeEvent) => {
        onChange(event.target.value as string);
    };

    return (
        <Select MenuProps={menuProps} value={value} label="Championship" onChange={handleChange}>
            {children}
        </Select>
    );
};

export default Selector;
