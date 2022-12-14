// @ts-ignore
import React from 'react';
import { AppProps } from 'next/app';
import {
    createTheme,
    CssBaseline,
    ThemeOptions,
    ThemeProvider,
} from '@mui/material';

const paletteOptions: ThemeOptions = {
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
};

const darkTheme = createTheme(paletteOptions);

const App: React.FunctionComponent<AppProps> = ({ Component, pageProps }) => (
    <ThemeProvider theme={darkTheme}>
        <CssBaseline />
        <Component {...pageProps} />
    </ThemeProvider>
);

export default App;
