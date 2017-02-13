<?php

namespace Vieira\Rospdf;

use Illuminate\Support\Facades\Response as ResponseFactory;
use Symfony\Component\HttpFoundation\Response;


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

    /**
     * Add an default header to document pages
     * @param \Cezpdf $document
     * @param bool $pageNumbers
     * @return void
     */
    public function addHeader(\Cezpdf &$document, $page = 'all', $pageNumbers = false)
    {
        $offsets = Helper::headerOffsets();

        $text = config('rospdf.header.main');

        $header = $document->openObject();

        $document->addText($offsets['x1'], $offsets['y1'], config('rospdf.fontsize'), $offsets['y2'], $text);

        if($pageNumbers){
            $document->addText($offsets['x1'], $offsets['y1'], config('rospdf.fontsize'), $document->ezGetCurrentPageNumber(), 0, 'right');
        }

        $document->line($offsets['x1'], $offsets['y1'], $offsets['x2'], $offsets['y2']);
        $document->closeObject();

        $document->addObject($header, $page);
    }

    /**
     * Add an default footer to document pages
     * @param \Cezpdf $document
     * @param bool $pageNumbers
     * @return void
     */
    public function addFooter(\Cezpdf &$document, $page = 'all', $pageNumbers = false)
    {
        $offsets = Helper::footerOffsets();

        $text = config('rospdf.header.main');

        $header = $document->openObject();

        $document->line($offsets['x1'], $offsets['y1'], $offsets['x2'], $offsets['y2']);

        $document->addText($offsets['x1'], $offsets['y1'], config('rospdf.fontsize'), $text);

        if($pageNumbers){
            $document->addText($offsets['x1'], $offsets['y1'], config('rospdf.fontsize'), $document->ezGetCurrentPageNumber(), 0, 'right');
        }

        $document->closeObject();

        $document->addObject($header, $page);
    }

    /**
     * Generates document as Stream response
     * @param \Cezpdf $document
     * @param string $fileName
     * @return Response
     * @throws \Exception
     */
    public function streamResponse(\Cezpdf &$document, $fileName = 'file')
    {
        try {
            $output = $document->ezStream([
                'Content-Disposition' => $fileName .'.pdf',
                'Accept-Ranges' => 1,
                'compress' => 0
            ]);

            $headers = array('Content-Type: application/pdf');
            return ResponseFactory::stream($output, 200, $headers);

        } catch (\Exception $e){
            if (config('app.debug')) {
                throw $e;
            }

            return new Response('Stream fail', 500);
        }
    }

    /**
     * Generates an file and returns a file download response
     * @param \Cezpdf $document
     * @param string $fileName
     */
    public function downloadResponse(\Cezpdf &$document, $fileName = 'file')
    {

    }

    /**
     * Saves the file to specified path
     * @param \Cezpdf $document
     * @param string $fileName
     * @param null $path
     * @return bool
     * @throws \Exception
     */
    public function saveTo(\Cezpdf &$document, $fileName = 'document', $path = null)
    {
        $defaultPath = storage_path() . DIRECTORY_SEPARATOR;

        if(!$path){
            $path = $defaultPath;
        }

        try {
            $file = fopen($path . $fileName.'pdf', 'wb+');
            $output = $document->ezOutput();
            fwrite($file, $output);
            fclose($file);
        } catch (\Exception $e){
            if (config('app.debug')) {
                throw $e;
            }

            return false;
        }

        return true;
    }
}
