<?php

namespace Careshop\Reviews\Controller\Customer;

use Magento\Review\Controller\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;
use Magento\Review\Model\Review;

class reviewPost extends ProductController
{
    /**
     * @return void
     */
    public function execute()  
    {
		
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $data = $this->reviewSession->getFormData(true);
		
        if ($data) {
            $rating = [];
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data = $this->getRequest()->getPostValue();
            $rating = $this->getRequest()->getParam('ratings', []);
        }

        $nickname = __('Anonymous');

        if (isset($data['reviewsSwitchCheckbox']) && $data['reviewsSwitchCheckbox'] == 'on') {
            $data['nickname'] = $this->customerSession->getCustomer()->getFirstname().' '.$this->customerSession->getCustomer()->getLastname();
        } else {
            $data['nickname'] = $nickname;
        }

		$id = $this->getRequest()->getParam('id');
        $order_item_id = $data['order_item_id'];
        if (!empty($data) && $order_item_id) {
            /** @var \Magento\Review\Model\Review $review */
            $review = $this->reviewFactory->create()->setData($data);
            $review->unsetData('review_id');

            $validate = $review->validate();
			
            if ($validate === true) {
                try {
                    $review->setEntityId($review->getEntityIdByCode(Review::ENTITY_PRODUCT_CODE))
                        ->setEntityPkValue($id)
						->setEntitySimpleId($data['entity_simple_id'])
                        ->setStatusId(Review::STATUS_PENDING)
                        ->setCustomerId($this->customerSession->getCustomerId())
                        ->setStoreId($this->storeManager->getStore()->getId())
                        ->setStores([$this->storeManager->getStore()->getId()])
                        ->save();

                    foreach ($rating as $ratingId => $optionId) {
						
                        $this->ratingFactory->create()
                            ->setRatingId($ratingId)
                            ->setReviewId($review->getId())
                            ->setCustomerId($this->customerSession->getCustomerId())
                            ->addOptionVote($optionId, $id);
                    }
                    $review->aggregate();

                    $review_id = $review->getReviewId();
                    if ($order_item_id) {
                        $resource = $this->_objectManager->get(\Magento\Framework\App\ResourceConnection::class);
                        $connection = $resource->getConnection();
                        $table_sale_item = $resource->getTableName('sales_order_item');
                        $sql_sale_item = "UPDATE $table_sale_item SET `review_customer` = '1' WHERE `item_id`= $order_item_id";
                        $connection->query($sql_sale_item);

                        $table_review = $resource->getTableName('review');
                        $sql_review = "UPDATE $table_review SET `order_item_id` = $order_item_id WHERE `review_id`= $review_id";
                        $connection->query($sql_review);
                    }

                    $this->messageManager->addSuccess(__('You submitted your review for moderation.'));
                } catch (\Exception $e) {
                    $this->reviewSession->setFormData($data);
                    $this->messageManager->addError(__('We can\'t post your review right now.'));
                }
            } else {
                $this->reviewSession->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $this->messageManager->addError($errorMessage);
                    }
                } else {
                    $this->messageManager->addError(__('We can\'t post your review right now.'));
                }
            }
        }
		return $this->_redirect('reviews/index/index');
	}
}
