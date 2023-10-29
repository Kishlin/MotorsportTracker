'use client';

import { Alert, Snackbar } from '@mui/material';
import {
    FunctionComponent,
    SyntheticEvent,
    createContext,
    ReactNode,
    useState,
    useMemo,
} from 'react';

export type SnackbarContextType = {
    showSnackAlert: (message: string) => void;
    message: string;
    open: boolean;
};

const defaultValue: SnackbarContextType = { open: false, message: '', showSnackAlert: () => {} };

export const SnackbarContext = createContext<SnackbarContextType>(defaultValue);

export const SnackbarProvider: FunctionComponent<{ children: ReactNode }> = ({ children }) => {
    const [message, setMessage] = useState('');
    const [open, setOpen] = useState(false);

    const handleClose = (event?: SyntheticEvent | Event, reason?: string) => {
        if ('clickaway' === reason) {
            return;
        }

        setOpen(false);
    };

    const showSnackAlert = (newMessage: string) => {
        setMessage(newMessage);
        setOpen(true);
    };

    const context = useMemo<SnackbarContextType>(
        () => ({
            showSnackAlert,
            message,
            open,
        }),
        [open, message],
    );

    return (
        <SnackbarContext.Provider value={context}>
            <Snackbar open={open} autoHideDuration={6000} onClose={handleClose}>
                <Alert elevation={6} variant="filled" onClose={handleClose} severity="success" sx={{ width: '100%' }}>
                    {message}
                </Alert>
            </Snackbar>
            {children}
        </SnackbarContext.Provider>
    );
};
