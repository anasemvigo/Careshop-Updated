<?php
declare(strict_types=1);

namespace Magento\Brandimage\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

class Index extends Action
{
    /**
     * @var $context
     */
    protected $_context;

    /**
     * @var $resource
     */
    protected $_resource;
    /**
     * @var $filesystem
     */
    protected $_filesystem;
    /**
     * @var $_cart
     */
    protected $_cart;

    /**
     * Index constructor.
     * @param Context $context
     * @param ResourceConnection $resource
     * @param Filesystem $filesystem
     * @param Cart $cart
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->_resource = $resource;
        $this->_filesystem = $filesystem;
        $this->_cart = $cart;
        parent::__construct($context);
    }

    /**
     * Get Product brand image
     * @render image/*
     */
    public function execute()
    {
        $attr = "brand";
        $attr_id = 202;
        $product_id = (int) @$_REQUEST["id"];
        $item_id = (int) @$_REQUEST["item_id"];
        if (!empty($item_id)) {
            $cart_items = $this->_cart->getItems();
            foreach ($cart_items as $item) {
                if ($item->getId() == $item_id) {
                    $product_id = $item->getProductId();
                }
            }
        }
        $data = "";
        $baseDir = "attribute/swatch";
        $type = "image/jpeg";

        $connection = $this->_resource->getConnection(
            \Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION
        );
        $mediapath = $this->_filesystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath();

        $sql =
            "SELECT s.value FROM " .
            $connection->getTableName("eav_attribute_option_swatch") .
            " s, " .
            $connection->getTableName("catalog_product_entity_int") .
            " o WHERE s.option_id = o.value AND o.entity_id = $product_id AND attribute_id =  $attr_id";

        $image = $connection->fetchOne($sql);
        if (!empty($image)) {
           
            $image_path = $mediapath . $baseDir . $image;
            
            
            if (file_exists($image_path)) {
                
                 
                $type = mime_content_type($image_path);
                
              
                $data = @file_get_contents($image_path);
                
                
            }
        }
        header("Content-Type: $type");
        echo $data;
        exit();
    }
}
