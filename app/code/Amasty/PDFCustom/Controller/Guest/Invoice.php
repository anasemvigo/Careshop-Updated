<?php

namespace Amasty\PDFCustom\Controller\Guest;

class Invoice extends \Amasty\PDFCustom\Controller\Sales\Invoice
{
    protected function getRedirect()
    {
        return $this->_redirect('sales/guest/form');
    }
}
