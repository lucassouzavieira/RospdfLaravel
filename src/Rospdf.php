<?php

namespace Vieira\Rospdf;

class Rospdf
{
    /**
     * Creates and configure an new ezPDF document
     * @return \Cezpdf
     */
    public function newDocument()
    {
        $document = new \Cezpdf(config('rospdf.paper'), config('rospdf.orientation'));

        $margins = config('rospdf.margins');

        $document->ezSetCmMargins($margins['top'], $margins['bottom'], $margins['left'], $margins['right']);
        $document->selectFont(config('rospdf.fontfamily'));

        return $document;
    }
}