import React from 'react';
import ReactDOM from 'react-dom';
import { createTheme, CssBaseline, ThemeProvider } from '@mui/material';

import reportWebVitals from './reportWebVitals';

import './index.css';

const darkTheme = createTheme({
    palette: {
        mode: 'dark',
    },
});

ReactDOM.render(
    <React.StrictMode>
        <ThemeProvider theme={darkTheme}>
            <CssBaseline />
            <p>Hello World.</p>
        </ThemeProvider>
    </React.StrictMode>,
    document.getElementById('root'),
);

if ('development' !== process.env.NODE_ENV) {
    // eslint-disable-next-line no-console
    reportWebVitals(console.log);
}
