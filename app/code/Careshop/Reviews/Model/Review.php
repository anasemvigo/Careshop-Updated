<?php
 
namespace Careshop\Reviews\Model;
 
class Review extends \Magento\Framework\Model\AbstractModel
{
	protected $objectManager;  
	protected $_resource;  
	protected $customerSession;  

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\App\ResourceConnection $resource
    ) {
		$this->objectManager = $objectManager;
        $this->_resource = $resource;
		$this->customerSession  = $customerSession;
    }
	
	public function dataReviewsPublished()
	{ 
		$adapter  = $this->_resource->getConnection(); 
		$review = $this->_resource->getTableName('review');
		$review_detail = $this->_resource->getTableName('review_detail');
		$customer_id = $this->customerSession->getId();
		$sql = 'SELECT a.*,b.* FROM '.$review.' as a INNER JOIN '.$review_detail.' as b ON a.review_id = b.review_id WHERE b.customer_id="'.$customer_id.'"';
		$data_query = $adapter->fetchAll($sql);
		return $data_query;
	}
	
	public function dataReviewsRating($id)
	{ 
		$adapter  = $this->_resource->getConnection(); 
		$tableName = $this->_resource->getTableName('rating_option_vote');
		$sql = 'SELECT percent FROM '.$tableName.' WHERE review_id="'.$id.'"';
		$data_query = $adapter->fetchRow($sql);
		return $data_query['percent'];
	}
	public function dataReviewsByProductId($product_id)
	{ 
		$adapter  = $this->_resource->getConnection(); 
		$review = $this->_resource->getTableName('review');
		$review_detail = $this->_resource->getTableName('review_detail');
		$customer_id = $this->customerSession->getId();
		$sql = 'SELECT a.review_id,a.entity_simple_id,b.review_id,b.customer_id FROM '.$review.' as a INNER JOIN '.$review_detail.' as b ON a.review_id = b.review_id WHERE a.entity_simple_id="'.$product_id.'" AND b.customer_id="'.$customer_id.'"';
		$data_query = $adapter->fetchRow($sql);
		return $data_query;
	}
	public function dataImproveReviewByProductId($product_id) 
	{ 
		$adapter  = $this->_resource->getConnection(); 
		$improve_review = $this->_resource->getTableName('improve_review');
		$customer_id = $this->customerSession->getId();
		$sql_improve = 'SELECT entity_id FROM '.$improve_review.' WHERE customer_id="'.$customer_id.'" AND product_id_key="'.$product_id.'"';
		$data_query_improve = $adapter->fetchRow($sql_improve);
		return $data_query_improve;
	}
}