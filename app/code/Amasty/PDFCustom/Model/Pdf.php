<?php

namespace Amasty\PDFCustom\Model;

use Dompdf\Dompdf;

class Pdf
{
    /**
     * Emulate Zend PDF behavior
     * Should contain HTML pages without html tag
     *
     * @var array
     */
    public $pages = [];

    private $doctypeHeader = '';

    /**
     * @var Dompdf
     */
    private $dompdf;

    /**
     * @var string
     */
    private $output;

    public function __construct()
    {
        $this->create();
    }

    /**
     * @return $this
     */
    public function create()
    {
        $this->output = null;
        $this->dompdf = new Dompdf([
            'isRemoteEnabled' => true,
            'logOutputFile' => false,
            'dpi' => '150'
        ]);

        return $this;
    }

    /**
     * set HTML which will be converted to PDF
     * each body tag will be processed as new page
     *
     * @param string $html
     *
     * @return $this
     */
    public function setHtml($html)
    {
        // remove doctype and html tag for multi templates in one pdf
        $html = preg_replace('~<(?:!DOCTYPE|/?(?:html))[^>]*>\s*~i', '', $html);
        $this->pages[] = $html;

        return $this;
    }

    private function replaceHeader($match)
    {
        if (strpos($match[0], 'DOCTYPE') !== false) {
            $this->doctypeHeader = $match[0];
        }

        return '';
    }

    /**
     * @param string $cssString
     */
    public function setCss($cssString)
    {
        $this->dompdf->getCss()->load_css($cssString, \Dompdf\Css\Stylesheet::ORIG_AUTHOR);
    }

    /**
     * @param array $options output options
     *
     * @return string|null
     */
    public function render($options = [])
    {
        if ($this->output === null) {
          
        //  print_r($this->prepareHtml());die;
          
          $this->dompdf->set_option('isHtml5ParserEnabled', true);
            $this->dompdf->loadHtml($this->prepareHtml());
  
            $this->dompdf->render();
            $this->output = $this->dompdf->output($options);
        }

        return $this->output;
    }

    /**
     * For compatibility with default Magento PDF processor
     *
     * @return \Zend_Pdf
     */
    public function convertToZendPDF()
    {
        return new \Zend_Pdf($this->render());
    }

    /**
     * render
     *
     * @return string
     */
    protected function prepareHtml()
    {
        $html = $this->doctypeHeader . implode('', $this->pages);
        $this->pages = [];
        $this->doctypeHeader = '';

        return $html;
    }
}
