'use client';

import { FunctionComponent, ReactNode } from 'react';
import Box from '@mui/material/Box';

declare type SessionContainerProps = {
    children: ReactNode,
};

const SessionContainer: FunctionComponent<SessionContainerProps> = ({ children }) => (
    <Box>
        {children}
    </Box>
);

export default SessionContainer;
