<?php

namespace Careshop\Reviews\Controller\Customer;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Review extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;
	protected $customerSession;
	protected $urlInterface;
	
	public function __construct( 
		Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\UrlInterface $urlInterface,
		PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;
		$this->customerSession = $customerSession;
		$this->urlInterface = $urlInterface;
		parent::__construct($context);
	} 

	public function execute()
	{
		if(!$this->customerSession->isLoggedIn()) {
			$this->customerSession->setAfterAuthUrl($this->urlInterface->getCurrentUrl());
			$this->customerSession->authenticate();
		} 
		$resultPage = $this->resultPageFactory->create();

		$resultPage->getConfig()->getTitle()->set(__('Reviews / Improvements'));

		return $resultPage;
	}
}
