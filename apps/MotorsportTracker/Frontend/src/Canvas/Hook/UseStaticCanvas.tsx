import { useRef, useEffect, MutableRefObject } from 'react';

export type StaticDraw = (ctx: CanvasRenderingContext2D) => void;

const useStaticCanvas = (aspectRatio: number, draw: StaticDraw, containerRef: MutableRefObject<any>) => {
    const canvasRef = useRef<HTMLCanvasElement>(null);

    useEffect(
        () => {
            const canvas = canvasRef.current;
            if (null === canvas) {
                return;
            }

            const context = canvas.getContext('2d');
            if (null === context) {
                return;
            }

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
