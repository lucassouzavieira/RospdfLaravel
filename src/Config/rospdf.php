<?php

return [
    'fontsize' => 12,

    'fontfamily' => 'Times-Roman',

    'paper' => 'a4',

    'orientation' => 'portrait',

    /*
     * Margins in cm
     */
    'margins' => [
        'left' => 2,
        'right' => 2,
        'top' => 2,
        'bottom' => 2,
    ],

    'header' => [
        'main' => 'Header Text',
        'pagenumbers' => true,
        'align' => 'left',
        'image' => [
            'path' => 'path/to/image',
            'align' => 'center',
        ],
    ],

    'footer' => [
        'main' => 'Footer Text',
        'pagenumbers' => true,
        'align' => 'left',
        'image' => [
            'path' => 'path/to/image',
            'align' => 'center',
        ],
    ],
];
