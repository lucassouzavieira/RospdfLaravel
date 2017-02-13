<?php

namespace Vieira\Rospdf;

abstract class Helper
{
    const PIXEL = 37.795;

    const A4_PORTRAIT_SIZE = [
        'x1' => 0,
        'y1' => 0,
        'x2' => 595.28,
        'y2' => 841.89
    ];

    const A4_LANDSCAPE_SIZE = [
        'x1' => 0,
        'y1' => 0,
        'x2' => 841.89,
        'y2' => 595.28
    ];


    /**
     * Calculates the header offsets
     * @return array
     */
    public static function headerOffsets()
    {
        $size = Helper::A4_PORTRAIT_SIZE;

        if(config('rospdf.orientation') == 'landscape'){
            $size = Helper::A4_LANDSCAPE_SIZE;
        }

        $margins = config('rospdf.margins');

        return [
            'x1' => $size['x1'] + $margins['left'] * Helper::PIXEL,
            'y1' => $size['y2'] - $margins['top'] * Helper::PIXEL,
            'x2' => $size['x2'] - $margins['right'] * Helper::PIXEL,
            'y2' => $size['y1'] - $margins['top'] * Helper::PIXEL,
        ];

    }

    /**
     * Calculates the footer offsets
     * @return array
     */
    public static function footerOffsets()
    {
        $size = Helper::A4_PORTRAIT_SIZE;

        if(config('rospdf.orientation') == 'landscape'){
            $size = Helper::A4_LANDSCAPE_SIZE;
        }

        $margins = config('rospdf.margins');

        return [
            'x1' => $size['x1'] + $margins['left'] * Helper::PIXEL,
            'y1' => $size['y1'] + $margins['bottom'] * Helper::PIXEL,
            'x2' => $size['x2'] - $margins['right'] * Helper::PIXEL,
            'y2' => $size['y1'] + $margins['bottom'] * Helper::PIXEL,
        ];
    }
}