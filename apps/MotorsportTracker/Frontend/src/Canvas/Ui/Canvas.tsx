import React, { useRef } from 'react';
import Box from '@mui/material/Box';

import useCanvas, { Draw } from '../Hook/UseCanvas';

declare type CanvasProps = {
    aspectRatio: number,
    draw: Draw,
};

const Canvas: React.FunctionComponent<CanvasProps> = ({ draw, aspectRatio }) => {
    const containerRef = useRef(null);
    const canvasRef = useCanvas(draw, aspectRatio, containerRef);

    return (
        <Box ref={containerRef}>
            <canvas ref={canvasRef} />
        </Box>
    );
};

export default Canvas;
