import { useRef, useEffect, MutableRefObject } from 'react';

export type StaticDraw = (ctx: CanvasRenderingContext2D) => void;

const useStaticCanvas = (aspectRatio: number, draw: StaticDraw, containerRef: MutableRefObject<any>) => {
    const canvasRef = useRef(null);

    useEffect(
        () => {
            const canvas = canvasRef.current;
            const context = canvas.getContext('2d');

            const { width } = containerRef.current.getBoundingClientRect();

            canvas.width = width - 2;
            canvas.height = (width - 2) / aspectRatio;

            draw(context);
        },
        [draw, canvasRef, containerRef, containerRef.current?.getBoundingClientRect().width],
    );

    return canvasRef;
};

export default useStaticCanvas;
