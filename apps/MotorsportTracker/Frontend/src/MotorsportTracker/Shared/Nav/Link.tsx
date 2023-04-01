import Typography from '@mui/material/Typography';
import React from 'react';

import useNavigate from '../../../Shared/Hooks/useNavigate';

declare type LinkProps = {
    children: string,
    to: string,
}

const Link: React.FunctionComponent<LinkProps> = ({ to, children }) => {
    const { redirectionTo } = useNavigate();

    return (
        <Typography
            onClick={redirectionTo(to)}
            sx={{ mx: 2, cursor: 'pointer' }}
            variant="h6"
            noWrap
        >
            {children}
        </Typography>
    );
};

export default Link;
