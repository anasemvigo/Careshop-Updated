<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PinBlooms\ExtendedSwatches\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Media
 */
class Media extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Catalog\Model\Product Factory
     */
    protected $productModelFactory;
    /**
     * @var \Magento\Swatches\Helper\Data
     */
    private $swatchHelper;
    /**
     * @var \Magento\PageCache\Model\Config
     */
    protected $config;
    protected $configurable;
    protected $grouped;
    /**
     * @param Context $context
     * @param \Magento\Catalog\Model\ProductFactory $productModelFactory
     * @param \Magento\Swatches\Helper\Data $swatchHelper
     * @param \Magento\PageCache\Model\Config $config
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ProductFactory $productModelFactory,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable,
        \Magento\GroupedProduct\Model\Product\Type\Grouped $grouped,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\PageCache\Model\Config $config
    ) {
        $this->productModelFactory = $productModelFactory;
        $this->swatchHelper        = $swatchHelper;
        $this->config              = $config;
        $this->configurable        = $configurable;
        $this->grouped             = $grouped;
        parent::__construct($context);
    }
    /**
     * Get product media for specified configurable product variation
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $productMedia = [];
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        /** @var \Magento\Framework\App\ResponseInterface $response */
        $response   = $this->getResponse();
        $productIds = $this->getRequest()->getParam('product_id');
		
		$prefix = "_".rand ( 1000 , 9999 )."_";
		
		
        if (is_array($productIds)) {
          $getParentId = $this->getRequest()->getParam('current_bundle_product_id');
            if ($getParentId) {
                $product        = $this->productModelFactory->create()->load((int) $getParentId);
                $productMediaar = $this->swatchHelper->getProductMediaGallery(
                    $product
                );
                if ($productMediaar['gallery']) {
                    $productMedia = $productMediaar;
                } else {
                    $productMedia = $productMediaar['gallery'];
                }
                }
            
			
			  
			  $prefix_keyflag = false;
			  
            foreach ($productIds as $key => $productId) {
                if ($productId) {
                    $product        = $this->productModelFactory->create()->load((int) $productId);
                    $productMediaar = $this->swatchHelper->getProductMediaGallery(
                        $product
                    );
                    if (isset($productMedia['gallery']) && $productMediaar['gallery']) {
                        $productMedia['gallery'] = $productMedia['gallery'] + $productMediaar['gallery'];
                    } else if(isset($productMedia['gallery'])) {
                       $productMedia['gallery'] = $productMedia['gallery'] + $productMediaar;
                    }else{
						
						 $productMedia = $productMedia + $productMediaar;
						 
						
						 
						  $prefix_keyflag = true;
						  
						 
						  
					}
					
					if($productMedia){
							foreach ($productMedia['gallery'] as $k => $v) {
								$productMedia['gallery'][$prefix . $k] = $v;
								unset($productMedia['gallery'][$k]);
							}
						}
						

						
						
                    //$resultJson->setHeader('X-Magento-Tags', implode(',', $product->getIdentities()));
                }
            }
			
			if($prefix_keyflag){
				$productMedia['large'] = "";
				$productMedia['medium']= "";
				$productMedia['small']= "";
				$productMedia['position']= "";
				$productMedia['isMain']= "";
			}
         
           /* foreach ($productMedia['gallery'] as $k => $v) {
                $productMedia['gallery'][$prefix . $k] = $v;
                unset($productMedia['gallery'][$k]);
            } 
            echo "<pre>";
                      
                          print_r($productMedia);
                          die("working here");*/
        } else {
			
			 $getParentId = $this->getRequest()->getParam('current_bundle_product_id');
            if ($getParentId) {
                $product        = $this->productModelFactory->create()->load((int) $getParentId);
                $productMediaar = $this->swatchHelper->getProductMediaGallery(
                    $product
                );
                if ($productMediaar['gallery']) {
                    $productMedia = $productMediaar;
                } else {
                    $productMedia = $productMediaar['gallery'];
                }
				
				if($productMedia){
							foreach ($productMedia['gallery'] as $k => $v) {
								$productMedia['gallery'][$prefix . $k] = $v;
								unset($productMedia['gallery'][$k]);
							}
						}
						
						
                }
				
				
            if ($productId = (int) $this->getRequest()->getParam('product_id')) {
              
			  $product      = $this->productModelFactory->create()->load($productId);
                $productMediaar = $this->swatchHelper->getProductMediaGallery(
                    $product
                );
					
				if(isset($productMedia['gallery']) && isset($productMediaar)){
					
					foreach ($productMediaar['gallery'] as $k => $v) {
						$productMediaar['gallery'][$prefix . $k] = $v;
						unset($productMediaar['gallery'][$k]);
					}
							
							
					$productMedia['gallery'] = array_merge($productMedia['gallery'] , $productMediaar['gallery']);
				}else{
					$productMedia = $productMedia + $productMediaar;
				}
				
				
				
            }
        }
		

						  
						  
        // $response->setPublicHeaders($this->config->getTtl());
        $resultJson->setData($productMedia);
        return $resultJson;
    }
    public function getParentId($childId)
    {
        /* for simple product of configurable product */
        $product = $this->configurable->getParentIdsByChild($childId);
        if (isset($product[0])) {
            return $product[0];
        }
        /* for simple product of Group product */
        $parentIds = $this->grouped->getParentIdsByChild($childId);
        /* or for Group/Bundle Product */
        $product->getTypeInstance()->getParentIdsByChild($childId);
    }
}
