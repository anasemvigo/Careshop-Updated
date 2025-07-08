<?php

namespace Amasty\PDFCustom\Controller\Guest;

class Creditmemo extends \Amasty\PDFCustom\Controller\Sales\Creditmemo
{
    protected function getRedirect()
    {
        return $this->_redirect('sales/guest/form');
    }
}
