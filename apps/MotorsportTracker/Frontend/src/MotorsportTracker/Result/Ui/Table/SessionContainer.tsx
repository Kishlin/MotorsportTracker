import Box from '@mui/material/Box';
import React from 'react';

declare type SessionContainerProps = {
    children: React.ReactNode,
};

const SessionContainer: React.FunctionComponent<SessionContainerProps> = ({ children }) => (
    <Box>
        {children}
    </Box>
);

export default SessionContainer;
