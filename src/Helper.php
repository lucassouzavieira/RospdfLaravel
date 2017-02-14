<?php

namespace Vieira\Rospdf;

abstract class Helper
{
    const PIXEL = 37.795;

    const A4_PORTRAIT_SIZE = [
        'x1' => 0,
        'y1' => 0,
        'x2' => 595.28,
        'y2' => 841.89,
    ];

    const A4_LANDSCAPE_SIZE = [
        'x1' => 0,
        'y1' => 0,
        'x2' => 841.89,
        'y2' => 595.28,
    ];

    /**
     * Calculates the header offsets.
     *
     * @return array
     */
    public static function headerOffsets()
    {
        $size = self::A4_PORTRAIT_SIZE;

        if (config('rospdf.orientation') == 'landscape') {
            $size = self::A4_LANDSCAPE_SIZE;
        }

        $margins = config('rospdf.margins');

        return [
            'x1' => $size['x1'] + (int) $margins['left'] * self::PIXEL,
            'y1' => $size['y2'] - (int) $margins['top'] * self::PIXEL,
            'x2' => $size['x2'] - (int) $margins['right'] * self::PIXEL,
            'y2' => $size['y2'] - (int) $margins['top'] * self::PIXEL,
        ];
    }

    /**
     * Calculates the footer offsets.
     *
     * @return array
     */
    public static function footerOffsets()
    {
        $size = self::A4_PORTRAIT_SIZE;

        if (config('rospdf.orientation') == 'landscape') {
            $size = self::A4_LANDSCAPE_SIZE;
        }

        $margins = config('rospdf.margins');

        return [
            'x1' => $size['x1'] + (int) $margins['left'] * self::PIXEL,
            'y1' => $size['y1'] + (int) $margins['bottom'] * self::PIXEL,
            'x2' => $size['x2'] - (int) $margins['right'] * self::PIXEL,
            'y2' => $size['y1'] + (int) $margins['bottom'] * self::PIXEL,
        ];
    }
}
