import { SvgIcon } from '@mui/material';
import React from 'react';

type FontAwesomeSvgIconProps = {
    icon: any;
};

const FontAwesomeSvgIcon = React.forwardRef<SVGSVGElement, FontAwesomeSvgIconProps>(
    (props, ref) => {
        const { icon } = props;

        const {
            icon: [width, height, , , svgPathData],
        } = icon;

        return (
            <SvgIcon ref={ref} viewBox={`0 0 ${width} ${height}`} color="action">
                {'string' === typeof svgPathData ? (
                    <path d={svgPathData} />
                ) : (
                    svgPathData.map((d: string, i: number) => (
                        <path style={{ opacity: 0 === i ? 0.4 : 1 }} d={d} />
                    ))
                )}
            </SvgIcon>
        );
    },
);

export default FontAwesomeSvgIcon;
