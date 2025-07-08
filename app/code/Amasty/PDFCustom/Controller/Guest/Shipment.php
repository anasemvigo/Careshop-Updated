<?php

namespace Amasty\PDFCustom\Controller\Guest;

class Shipment extends \Amasty\PDFCustom\Controller\Sales\Shipment
{
    protected function getRedirect()
    {
        return $this->_redirect('sales/guest/form');
    }
}
