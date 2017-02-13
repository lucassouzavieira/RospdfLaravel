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
     * @param array|null $options
     * @return void
     */
    public function addHeader(\Cezpdf &$document, $pageNumbers = true, array $options = null)
    {

    }

    /**
     * Add an default footer to document pages
     * @param \Cezpdf $document
     * @param bool $pageNumbers
     * @param array|null $options
     * @return void
     */
    public function addFooter(\Cezpdf &$document, $pageNumbers = true, array $options = null)
    {

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
     * @param string $path
     */
    public function saveTo(\Cezpdf &$document, $path = null)
    {

    }
}
