'use client';

import { createTheme, ThemeProvider, ThemeOptions } from '@mui/material/styles';
import { CssBaseline } from '@mui/material';
import { ReactNode } from 'react';

declare module '@mui/material/styles' {
    interface ThemeOptions {
        layout?: {
            header?: {
                base?: string,
            },
        };
    }
}

const paletteOptions: ThemeOptions = {
    layout: {
        header: {
            base: '#232323',
        },
    },
    palette: {
        mode: 'dark',
        primary: {
            main: '#4268af',
        },
        secondary: {
            main: '#f5b300',
        },
        background: {
            default: '#121212',
            paper: 'rgba(43,43,43,0.74)',
        },
        divider: 'rgba(255,255,255,0.12)',
    },
    components: {
        MuiCssBaseline: {
            styleOverrides: {
                body: {
                    scrollbarColor: '#6b6b6b #2b2b2b',
                    '&::-webkit-scrollbar, & *::-webkit-scrollbar': {
                        minWidth: 15,
                        backgroundColor: '#2b2b2b',
                    },
                    '&::-webkit-scrollbar-thumb, & *::-webkit-scrollbar-thumb': {
                        borderRadius: 8,
                        backgroundColor: '#6b6b6b',
                        minHeight: 24,
                        border: '3px solid #2b2b2b',
                    },
                    '&::-webkit-scrollbar-thumb:focus, & *::-webkit-scrollbar-thumb:focus': {
                        backgroundColor: '#959595',
                    },
                    '&::-webkit-scrollbar-thumb:active, & *::-webkit-scrollbar-thumb:active': {
                        backgroundColor: '#959595',
                    },
                    '&::-webkit-scrollbar-thumb:hover, & *::-webkit-scrollbar-thumb:hover': {
                        backgroundColor: '#959595',
                    },
                    '&::-webkit-scrollbar-corner, & *::-webkit-scrollbar-corner': {
                        backgroundColor: '#2b2b2b',
                    },
                },
            },
        },
    },
};

const darkTheme = createTheme(paletteOptions);

const MuiLayout = ({
    children,
}: {
    children: ReactNode
}) => (
    <ThemeProvider theme={darkTheme}>
        <CssBaseline />
        {children}
    </ThemeProvider>
);

export default MuiLayout;
