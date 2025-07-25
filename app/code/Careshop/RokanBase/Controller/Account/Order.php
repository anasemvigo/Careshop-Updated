<?php

namespace Careshop\RokanBase\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Order extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;

	public function __construct(
		Context $context,
		PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;

		parent::__construct($context);
	}

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();

		return $resultPage;
	}
}
