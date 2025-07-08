<?php
/**
 * @copyright: Copyright © 2024 Ewall, Inc. All rights reserved.
 * @See COPYING.txt for license details.
 * @author   : Ewall
 * @keywords : Module Ewall_MetaOgTwitter
 */
declare(strict_types=1);

namespace Ewall\MetaOgTwitter\Block\Twitter;

use Ewall\MetaOgTwitter\Helper\Config;
use Ewall\MetaOgTwitter\Helper\Data;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Cms\Model\Page as CmsPage;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Block\Product\Context as ProductContext;
use Magento\Catalog\Model\ProductRepository;

class MetaTags extends Template
{
    /**
     * @var Config
     */
    protected $seoConfig;

    /**
     * @var array
     */
    protected $metaTags = [];

    /**
     * @var string
     */
    protected $metaPrefix = 'twitter:';

    /**
     * @var string
     */
    protected $metaAttribute = 'name';

    /**
     * @var CmsPage
     */
    protected $page;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * constructor
     *
     * @param Context $context
     * @param Data $helper
     * @param Config $config
     * @param Escaper $escaper
     * @param CmsPage $page
     * @param UrlInterface $url
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        Config $config,
        Escaper $escaper,
        CmsPage $page,
        UrlInterface $url,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->seoConfig = $config;
        $this->escaper = $escaper;
        $this->page = $page;
        $this->url = $url;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Retrieve and render Twitter meta tags
     *
     * @return string
     */
    public function renderTwitterMetaTags(): string
    {
        $metaHtml = '';
        $tagData = [];
        $fullActionName = $this->getRequest()->getFullActionName();

        switch ($fullActionName) {
            case 'cms_index_index':
            case 'cms_page_view':
                if ($this->seoConfig->isEnableCMSTwitterTag() && $this->page->getId()) {
                    $title = $this->seoConfig->isSetCMSTwitterTitle() == 'page_title' ? $this->page->getTitle() : $this->page->getMetaTitle();
                    $description = $this->seoConfig->isSetCMSTwitterDescription() == 'page_description' ? $this->helper->getOptimizeDescription($this->page->getContent()) : $this->page->getMetaDescription();
                    $tagData['title'] = (string)$title;
                    $tagData['description'] = (string)$description;
                    $tagData['card'] = $this->seoConfig->isSetTwitterCardType();
                    if ($this->seoConfig->isSetTwitterCardType() === 'app') {
                        $tagData = array_merge($tagData, $this->appIntergration($tagData['card']));
                    }
                    if ($this->seoConfig->isSetTwitterCardType() === 'player') {
                        $tagData = array_merge($tagData, $this->playerIntergration($tagData['card']));
                    }
                    $tagData['site'] = $this->seoConfig->getTwitterUsername();
                    $tagData['creator'] = $this->seoConfig->getTwitterAuthor();
                }
                break;

            case 'catalog_category_view':
                $categoryId = $this->helper->getCurrentCategoryId();
                if ($this->seoConfig->isEnableCategoryTwitterTag() && $categoryId) {
                    $category = $this->categoryRepository->get($categoryId);
                    $title = $this->seoConfig->isSetCategoryTwitterTitle() == 'category_name' ? $category->getName() : $category->getMetaTitle();
                    $description = $this->seoConfig->isSetCategoryTwitterDescription() == 'category_description' ? $this->helper->getOptimizeDescription($category->getDescription()) : $category->getMetaDescription();
                    $tagData['title'] = (string)$title;
                    $tagData['url'] = (string)$category->getUrl();
                    $tagData['description'] = (string)$description;
                    $tagData['image'] = $this->helper->getCategoryImageUrl($category);
                    $tagData['card'] = $this->seoConfig->isSetTwitterCardType();
                    if ($this->seoConfig->isSetTwitterCardType() === 'app') {
                        $tagData['text:title'] = $tagData['title'];
                        $tagData = array_merge($tagData, $this->appIntergration($tagData['card']));
                    }
                    if ($this->seoConfig->isSetTwitterCardType() === 'player') {
                        $tagData = array_merge($tagData, $this->playerIntergration($tagData['card']));
                    }
                    $tagData['site'] = $this->seoConfig->getTwitterUsername();
                    $tagData['creator'] = $this->seoConfig->getTwitterAuthor();
                }
                break;

            case 'catalog_product_view':
                $productId = $this->helper->getCurrentProductId();
                if ($this->seoConfig->isEnableProductTwitterTag() && $productId) {
                    $product = $this->productRepository->getById($productId);
                    $title = $this->seoConfig->isSetProductTwitterTitle() == 'product_name' ? $product->getName() : $product->getMetaTitle();
                    $description = $this->seoConfig->isSetProductTwitterDescription() == 'product_description' ? $this->helper->getOptimizeDescription($product->getDescription()) : $product->getMetaDescription();
                    $tagData['title'] = (string)$title;
                    $tagData['description'] = (string)$description;
                    $tagData['image'] = (string)$this->helper->getImage($product, 'product_base_image')->getImageUrl();
                    $tagData['url'] = (string)$product->getProductUrl();
                    $tagData['card'] = $this->seoConfig->isSetTwitterCardType();
                    if ($this->seoConfig->isSetTwitterCardType() === 'app') {
                        $tagData = array_merge($tagData, $this->appIntergration($tagData['card']));
                    }
                    if ($this->seoConfig->isSetTwitterCardType() === 'player') {
                        $tagData = array_merge($tagData, $this->playerIntergration($tagData['card']));
                    }
                    $tagData['site'] = $this->seoConfig->getTwitterUsername();
                    $tagData['creator'] = $this->seoConfig->getTwitterAuthor();
                }
                break;

            default:
                break;
        }

        if (array_filter($tagData)) {
            foreach ($tagData as $tagKey => $tagValue) {
                if (!empty($tagValue)) {
                    $metaHtml .= $this->createMetaTag($tagKey, $tagValue) . PHP_EOL;
                }
            }
        }

        return $metaHtml;
    }

    /**
     * Player Inergration
     *
     * @param string $type
     */
    public function playerIntergration($type)
    {
        $tagData = [];
        if($type == 'player') {
            if(!empty($this->seoConfig->getPayerUrl())) {
                $tagData['player'] = $this->seoConfig->getPayerUrl();
            }
            if(!empty($this->seoConfig->getPayerWidth())) {
                $tagData['player:width'] = $this->seoConfig->getPayerWidth();
            }
            if(!empty($this->seoConfig->getPayerHeight())) {
                $tagData['player:height'] = $this->seoConfig->getPayerHeight();
            }

            return$tagData;
        }
    }

    /**
     * App Intergration
     *
     * @param string $type
     */
    public function appIntergration($type)
    {
        $tagData = [];
        if($type == 'app') {
            if(!empty($this->seoConfig->getIphoneAppId())) {
                $tagData['app:id:iphone'] = $this->seoConfig->getIphoneAppId();
            }
            if(!empty($this->seoConfig->getIphoneAppName())) {
                $tagData['app:name:iphone'] = $this->seoConfig->getIphoneAppName();
            }
            if(!empty($this->seoConfig->getIphoneAppUrl())) {
                $tagData['app:url:iphone'] = $this->seoConfig->getIphoneAppUrl();
            }

            if(!empty($this->seoConfig->getIpadAppId())) {
                $tagData['app:id:ipad'] = $this->seoConfig->getIpadAppId();
            }
            if(!empty($this->seoConfig->getIpadAppName())) {
                $tagData['app:name:ipad'] = $this->seoConfig->getIpadAppName();
            }
            if(!empty($this->seoConfig->getIpadAppUrl())) {
                $tagData['app:url:ipad'] = $this->seoConfig->getIpadAppUrl();
            }

            if(!empty($this->seoConfig->getAndroidAppId())) {
                $tagData['app:id:googleplay'] = $this->seoConfig->getAndroidAppId();
            }
            if(!empty($this->seoConfig->getAndroidAppName())) {
                $tagData['app:name:googleplay'] = $this->seoConfig->getAndroidAppName();
            }
            if(!empty($this->seoConfig->getAndroidAppUrl())) {
                $tagData['app:url:googleplay'] = $this->seoConfig->getAndroidAppUrl();
            }
            return $tagData;
        }
    }

    /**
     * Populate meta tags array with key-value pairs
     *
     * @param string $tagKey
     * @param string $tagValue
     * @return $this
     */
    public function populateMetaTags(string $tagKey, string $tagValue): self
    {
        $this->metaTags[$tagKey] = $tagValue;
        return $this;
    }

    /**
     * Generate the HTML output for the meta tags
     *
     * @return string
     */
    public function generateMetaHtml(): string
    {
        $htmlOutput = [];

        foreach ($this->metaTags as $key => $value) {
            if (!empty($value)) {
                $htmlOutput[] = $this->createMetaTag($key, $value);
            }
        }

        $this->clearMetaTags();
        return implode(PHP_EOL, $htmlOutput);
    }

    /**
     * Create a single meta tag
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    public function createMetaTag(string $key, string $value): string
    {
        return sprintf(
            '<meta %s="%s%s" content="%s" />',
            $this->escaper->escapeHtml($this->metaAttribute, ENT_QUOTES, 'UTF-8'),
            $this->escaper->escapeHtml($this->metaPrefix, ENT_QUOTES, 'UTF-8'),
            $this->escaper->escapeHtml(strip_tags($key), ENT_QUOTES, 'UTF-8'),
            $this->escaper->escapeHtml(strip_tags($value), ENT_QUOTES, 'UTF-8'),
            PHP_EOL
        );
    }

    /**
     * Clear the internal meta tags array after rendering
     */
    public function clearMetaTags(): void
    {
        $this->metaTags = [];
    }
}
