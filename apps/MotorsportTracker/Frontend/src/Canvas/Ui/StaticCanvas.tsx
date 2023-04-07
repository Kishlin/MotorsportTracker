import React, { useRef } from 'react';
import Box from '@mui/material/Box';

import useStaticCanvas, { StaticDraw } from '../Hook/UseStaticCanvas';

declare type StaticCanvasProps = {
    aspectRatio: number,
    draw: StaticDraw,
};

const StaticCanvas: React.FunctionComponent<StaticCanvasProps> = ({ aspectRatio, draw }) => {
    const containerRef = useRef(null);
    const canvasRef = useStaticCanvas(aspectRatio, draw, containerRef);

    return (
        <Box ref={containerRef}>
            <canvas ref={canvasRef} style={{ border: '1px solid white', marginTop: '200px' }} />
        </Box>
    );
};

export default StaticCanvas;
