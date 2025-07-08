<?php

namespace Amasty\PDFCustom\Controller\Guest;

class Order extends \Amasty\PDFCustom\Controller\Sales\Order
{
    protected function getRedirect()
    {
        return $this->_redirect('sales/guest/form');
    }
}
