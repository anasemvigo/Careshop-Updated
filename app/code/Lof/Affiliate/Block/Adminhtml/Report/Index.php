<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_Affiliate
 *
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\Affiliate\Block\Adminhtml\Report;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Lof\Affiliate\Model\AccountAffiliate
     */
    protected $_account;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Lof\Affiliate\Model\AccountAffiliate $account
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Lof\Affiliate\Model\AccountAffiliate $account
    )
    {
        $this->_account = $account;
        parent::__construct($context);
    }

    public function getAllAccount()
    {
        $collection = $this->_account->getCollection();
        $account_array = array();
        $option = "<select id='aff_email'>";
        foreach ($collection->getData() as $key) {
            $option .= '<option value=' . $key['email'] . '>' . $key['email'] . '</option>';
        }
        $option .= "</select>";
        return $option;
    }

}
