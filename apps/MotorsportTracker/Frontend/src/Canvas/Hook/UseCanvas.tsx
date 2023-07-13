import { useRef, useEffect, MutableRefObject } from 'react';

export type Draw = (ctx: CanvasRenderingContext2D, frameCount: number) => void;

const useCanvas = (draw: Draw, aspectRatio: number, containerRef: MutableRefObject<any>) => {
    const canvasRef = useRef(null);

    useEffect(
        () => {
            let frameCount = 0;
            let animationFrameId: number;

            const render = () => {
                const canvas = canvasRef.current;
                const context = canvas.getContext('2d');

                const { width } = containerRef.current.getBoundingClientRect();

                canvas.width = width - 1;
                canvas.height = canvas.width / aspectRatio;

                frameCount += 1;
                draw(context, frameCount);
                animationFrameId = window.requestAnimationFrame(render);
            };

            render();

            return () => {
                window.cancelAnimationFrame(animationFrameId);
            };
        },
        [draw, containerRef],
    );

    return canvasRef;
};

export default useCanvas;
