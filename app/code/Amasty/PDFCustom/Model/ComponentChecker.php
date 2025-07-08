<?php

namespace Amasty\PDFCustom\Model;

class ComponentChecker
{
    /**
     * @return bool
     */
    public function isComponentsExist()
    {
        try {
            $classExists = class_exists(\Dompdf\Dompdf::class);
        } catch (\Exception $e) {
            $classExists = false;
        }

        return $classExists;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getComponentsErrorMessage()
    {
        return __(
            "To use PDF customizer, please install the library dompdf/dompdf since it is required for proper "
            . "PDF customizer functioning. To do this, run the command ".
            "\"composer require dompdf/dompdf\" in the main site folder."
        );
    }
}
