<?php
/**
 * @copyright: Copyright © 2024 Ewall, Inc. All rights reserved.
 * @See COPYING.txt for license details.
 * @author   : Ewall
 * @keywords : Module Ewall_MetaOgTwitter
 */
declare(strict_types=1);

namespace Ewall\MetaOgTwitter\Helper;

use Psr\Log\LoggerInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Registry;
use Ewall\MetaOgTwitter\Helper\Config;
use Magento\Catalog\Block\Product\Image;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;

class Data extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManagerInterface;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ImageBuilder
     */
    protected $imageBuilder;

    /**
     * @var LayerResolver
     */
    protected $layerResolver;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * constructor
     *
     * @param Config $config
     * @param Escaper $escaper
     * @param Registry $registry
     * @param LoggerInterface $logger
     * @param ImageBuilder $imageBuilder
     * @param LayerResolver $layerResolver
     * @param StoreManagerInterface $storeManagerInterface
     */
    public function __construct(
        Config $config,
        Escaper $escaper,
        Registry $registry,
        LoggerInterface $logger,
        ImageBuilder $imageBuilder,
        LayerResolver $layerResolver,
        StoreManagerInterface $storeManagerInterface
    ) {
        $this->config = $config;
        $this->escaper = $escaper;
        $this->registry = $registry;
        $this->logger = $logger;
        $this->imageBuilder = $imageBuilder;
        $this->layerResolver = $layerResolver;
        $this->storeManagerInterface = $storeManagerInterface;
    }

    /**
     * Get current category ID
     *
     * @return int|null
     */
    public function getCurrentCategoryId(): ?int
    {
        $currentCategory = $this->layerResolver->get()->getCurrentCategory();
        return $currentCategory ? (int)$currentCategory->getId() : null;
    }

    /**
     * Get category image URL
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return string|null
     */
    public function getCategoryImageUrl($category): ?string
    {
        $imageUrl = null;
        $image = $category->getImageUrl();
        if ($image) {
            $imageUrl = $this->storeManagerInterface->getStore()->getBaseUrl() . $image;
        }
        return $imageUrl;
    }

    /**
     * Get current product ID
     *
     * @return int|null
     */
    public function getCurrentProductId(): ?int
    {
        $product = $this->getCurrentProduct();
        return $product ? (int)$product->getId() : null;
    }

    /**
     * Get current product
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Get Optimize Description
     * 
     * @param mixed $description
     */
    public function getOptimizeDescription($description)
    {
        $string = '';
        if(!empty($description)) {
            $string = $description;
            $string = html_entity_decode($string);
            $string = preg_replace('#<br[^>]*?>#siu', "\n", $string);
            $string = preg_replace(
                [
                    '#<head[^>]*?>.*?</head>#siu',
                    '#<style[^>]*?>.*?</style>#siu',
                    '#<script[^>]*?.*?</script>#siu',
                    '#<object[^>]*?.*?</object>#siu',
                    '#<embed[^>]*?.*?</embed>#siu',
                    '#<applet[^>]*?.*?</applet>#siu',
                    '#<noframes[^>]*?.*?</noframes>#siu',
                    '#<noscript[^>]*?.*?</noscript>#siu',
                    '#<noembed[^>]*?.*?</noembed>#siu'
                ],
                '',
                $string
            );
        }
        return $string;
    }

    /**
     * Get Image
     *
     * @param mixed $product
     * @param String $imageId
     * @param array $attributes
     */
    public function getImage(CatalogProduct $product, string $imageId, array $attributes = []) : Image
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
    
    /**
     * Retrieve Website Title
     */
    public function fetchWebsiteTitle()
    {
        $websiteTitle = null;
        try {
            $websiteTitle = $this->storeManagerInterface->getWebsite()->getName();
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            $this->logger->critical('Error fetching website title: ' . $exception->getMessage());
        } catch (\Exception $exception) {
            $this->logger->emergency('An unexpected error occurred: ' . $exception->getMessage());
        }
        return $websiteTitle;
    }

}
