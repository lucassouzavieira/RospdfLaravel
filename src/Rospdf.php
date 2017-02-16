<?php

namespace Vieira\Rospdf;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class Rospdf
{
    /**
     * default spacing used for show some elements.
     *
     * @var int
     */
    private $spacing = 5;

    /**
     * default spacing used for show table elements.
     *
     * @var int
     */
    private $tableSpacing = 25;

    /**
     * Default value to x position to show page number
     * @var float|int
     */
    private $xPageNumber;

    /**
     * Default value to y position to show page number
     * @var float|int
     */
    private $yPageNumber = 50;

    public function __construct()
    {
        $size = Helper::getSize();

        $this->xPageNumber = $size['x2'] / 2;
    }

    /**
     * Creates and configure an new ezPDF document.
     *
     * @return \Cezpdf
     */
    public function newDocument(array $options = null)
    {
        // TODO make possible override default configurations
        
        $document = new \Cezpdf(config('rospdf.paper'), config('rospdf.orientation'));
        $margins = config('rospdf.margins');

        $document->ezSetCmMargins($margins['top'], $margins['bottom'], $margins['left'], $margins['right']);
        $document->selectFont(config('rospdf.fontfamily'));

        $document->ezStartPageNumbers($this->xPageNumber, $this->yPageNumber, config('rospdf.fontsize'), 'PAGENUM', 1);

        return $document;
    }

    /**
     * Add an default header to document pages.
     *
     * @param \Cezpdf $document
     * @param bool    $pageNumbers
     */
    public function addHeader(\Cezpdf &$document, $page = 'all', $pageNumbers = false)
    {
        $offsets = Helper::headerOffsets();

        $text = config('rospdf.header.main');

        $header = $document->openObject();

        $document->addText($offsets['x1'] + $this->spacing, $offsets['y1'] + $this->spacing, config('rospdf.fontsize'), $text);

        if ($pageNumbers) {
            // TODO review page numbers at header
            $document->addText($offsets['x1'], $offsets['y1'], config('rospdf.fontsize'), '{PAGENUM}', 0, 'right');
        }

        $document->line($offsets['x1'], $offsets['y1'], $offsets['x2'], $offsets['y2']);
        $document->closeObject();

        $document->addObject($header, $page);
    }

    /**
     * Add an default footer to document pages.
     *
     * @param \Cezpdf $document
     */
    public function addFooter(\Cezpdf &$document, $page = 'all')
    {
        $offsets = Helper::footerOffsets();

        $text = config('rospdf.footer.main');
        $pageNumbers = config('rospdf.footer.pagenumbers');
        $align = config('rospdf.footer.align');

        $footer = $document->openObject();

        $document->line($offsets['x1'], $offsets['y1'], $offsets['x2'], $offsets['y2']);

        $document->addText($offsets['x1'] + $this->spacing, $offsets['y1'] - 2 * $this->spacing, config('rospdf.fontsize'), $text, 0, $align);

        if ($pageNumbers) {
            $document->addText($offsets['x1'] + $this->spacing, $offsets['y1'] - 4 * $this->spacing, config('rospdf.fontsize'), '{PAGENUM}', 0, $align);
        }

        $document->closeObject();

        $document->addObject($footer, $page);
    }

    /**
     * Create an table in document from an given Collection.
     *
     * @param \Cezpdf    $document
     * @param Collection $collection
     * @param array      $columns
     * @param string     $title      Table title
     * @param array      $options    Options array
     */
    public function addTableFromCollection(\Cezpdf &$document, Collection $collection, array $columns, $title = '', array $options = null)
    {
        $data = $collection->toArray();

        if (is_null($options)) {
            $options = [
                'showHeadings' => 1,
                'shaded' => 1,
                'shadeCol' => array(0.7, 0.7, 0.7),
                'fontSize' => 10,
                'titleFontSize' => 12,
                'xPos' => 'center',
                'xOrientation' => 'center',
                'nextPageY' => true,
            ];
        }

        $document->ezSetY(Helper::getTableHeightPosition($this->tableSpacing));
        $document->ezTable($data, $columns, $title, $options);
    }

    /**
     * Generates document as Stream response.
     *
     * @param \Cezpdf $document
     * @param string  $fileName
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function streamResponse(\Cezpdf &$document, $fileName = 'file')
    {
        try {
            $output = $document->ezStream([
                'Content-Disposition' => $fileName.'.pdf',
                'Accept-Ranges' => 1,
                'compress' => 0,
            ]);

            $headers = array('Content-Type: application/pdf');

            return ResponseFactory::stream($output, 200, $headers);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return new Response('Stream fail', 500);
        }
    }

    /**
     * Generates an file and returns a file download response.
     *
     * @param \Cezpdf $document
     * @param string  $fileName
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function downloadResponse(\Cezpdf &$document, $fileName = 'file')
    {
        $path = storage_path().DIRECTORY_SEPARATOR;

        try {
            $file = fopen($path.$fileName.'.pdf', 'wb+');
            $output = $document->ezOutput();
            fwrite($file, $output);
            fclose($file);

            $headers = ['Content-type: application/pdf'];
            $response = ResponseFactory::download($path.$fileName.'.pdf', $fileName.'.pdf', $headers);
            unlink($path.$fileName.'.pdf');

            return $response;
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return false;
        }
    }

    /**
     * Saves the file to specified path.
     *
     * @param \Cezpdf $document
     * @param string  $fileName
     * @param null    $path
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function saveTo(\Cezpdf &$document, $fileName = 'document', $path = null)
    {
        $defaultPath = storage_path().DIRECTORY_SEPARATOR;

        if (!$path) {
            $path = $defaultPath;
        }

        try {
            $file = fopen($path.$fileName.'.pdf', 'wb+');
            $output = $document->ezOutput();
            fwrite($file, $output);
            fclose($file);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            return false;
        }

        return true;
    }
}
