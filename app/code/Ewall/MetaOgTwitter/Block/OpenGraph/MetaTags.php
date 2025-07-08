<?php
/**
 * @copyright: Copyright © 2024 Ewall, Inc. All rights reserved.
 * @See COPYING.txt for license details.
 * @author   : Ewall
 * @keywords : Module Ewall_MetaOgTwitter
 */
declare(strict_types=1);

namespace Ewall\MetaOgTwitter\Block\OpenGraph;

use Ewall\MetaOgTwitter\Helper\Data;
use Ewall\MetaOgTwitter\Helper\Config;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Cms\Model\Page as CmsPage;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Block\Product\Context as ProductContext;

class MetaTags extends Template
{

    /**
     * @var CmsPage
     */
    protected $page;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Config
     */
    protected $config;

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
     * @var array
     */
    protected $metaTags = [];

    /**
     * @var string
     */
    protected $tagPrefix = '';

    /**
     * @var string
     */
    protected $metaAttribute = 'name';

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * constructor
     *
     * @param Context $context
     * @param CmsPage $page
     * @param Data $helper
     * @param Config $config
     * @param Escaper $escaper
     * @param UrlInterface $url
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        CmsPage $page,
        Data $helper,
        Config $config,
        Escaper $escaper,
        UrlInterface $url,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        array $data = []
    ) {
        $this->page = $page;
        $this->helper = $helper;
        $this->config = $config;
        $this->url = $url;
        $this->escaper = $escaper;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }

    /**
     * Render Og Meta Tags
     */
    public function renderOgMetaTags(): string
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        switch ($fullActionName) {
            case 'cms_index_index':
            case 'cms_page_view':
                if ($this->config->isEnableCMSOgTag() && $this->page->getId()) {
                    $this->renderCMSPage();
                }
                break;
            case 'catalog_category_view':
                $categoryId = $this->helper->getCurrentCategoryId();
                if ($this->config->isEnableCategoryOgTag() && !empty($categoryId)) {
                    $this->renderCategoryPage();
                }
                break;
            case 'catalog_product_view':
                $productId = $this->helper->getCurrentProductId();
                if ($this->config->isEnableProductOgTag() && !empty($productId)) {
                    $this->renderProductPage();
                }
                break;
            default:
                break;
        }

        return $this->setTagPrefix('og:')->setMetaAttribute('property')->renderMetaTags();
    }

    /**
     * Render CMS Page
     */
    public function renderCMSPage()
    {
        $title = $this->config->isSetCMSOgTitle() == 'page_title' ? $this->page->getTitle() : $this->page->getMetaTitle();
        $description = $this->config->isSetCMSOgDescription() == 'page_description' ? $this->helper->getOptimizeDescription($this->page->getContent()) : $this->page->getMetaDescription();
        $this->addMetaTag('title', (string) $title);
        $this->addMetaTag('url', (string) $this->url->getUrl($this->page->getIdentifier()));
        $this->addMetaTag('description', (string) $description);
        $this->addMetaTag('site_name', (string) $this->helper->fetchWebsiteTitle());
    }

    /**
     * Render Category Page
     */
    public function renderCategoryPage()
    {
        $categoryId = $this->helper->getCurrentCategoryId();
        if ($categoryId) {
            $category = $this->categoryRepository->get($categoryId);
            $title = $this->config->isSetCategoryOgTitle() == 'category_name' ? $category->getName() : $category->getMetaTitle();
            $description = $this->config->isSetCategoryOgDescription() == 'category_description' ? $this->helper->getOptimizeDescription($category->getDescription()) : $category->getMetaDescription();
            $this->addMetaTag('title', (string) $title);
            $this->addMetaTag('description', (string) $description);
            $this->addMetaTag('url', (string) $category->getUrl());
            $this->addMetaTag('image', (string) $this->helper->getCategoryImageUrl($category));
            $this->addMetaTag('site_name', (string) $this->helper->fetchWebsiteTitle());
        }
    }

    /**
     * Render Product Page
     */
    public function renderProductPage()
    {
        $productId = $this->helper->getCurrentProductId();
        if ($productId) {
            $product = $this->productRepository->getById($productId);
            $title = $this->config->isSetProductOgTitle() == 'product_name' ? $product->getName() : $product->getMetaTitle();
            $description = $this->config->isSetProductOgDescription() == 'product_description' ? $this->helper->getOptimizeDescription($product->getDescription()) : $product->getMetaDescription();
            $this->addMetaTag('title', (string) $title);
            $this->addMetaTag('description', (string) $description);
            $this->addMetaTag('url', $product->getProductUrl());
            $this->addMetaTag('image', (string) $this->helper->getImage($product, 'product_base_image')->getImageUrl());
            $this->addMetaTag('site_name', (string)$this->helper->fetchWebsiteTitle());
            $this->addMetaTag('type', 'product');
            $this->addMetaTag('product:price:amount', (string) $product->getFinalPrice());
        }
    }

    /**
     * Set Tag Prefix
     *
     * @param string $prefix
     */
    public function setTagPrefix(string $prefix): self
    {
        $this->tagPrefix = $prefix;
        return $this;
    }

    /**
     * Set Meta Attribute
     *
     * @param string $attribute
     */
    public function setMetaAttribute(string $attribute): self
    {
        $this->metaAttribute = $attribute;
        return $this;
    }

    /**
     * Add Meta Tag
     *
     * @param string $key
     * @param mixed $value
     */
    public function addMetaTag(string $key, $value): self
    {
        $this->metaTags[$key] = $value;
        return $this;
    }

    /**
     * Add Meta Tag
     *
     * @param string $key
     * @param mixed $value
     */
    public function renderMetaTags(): string
    {
        $htmlOutput = $this->generateHtmlFromMetaTags($this->metaTags);
        $this->resetMetaTags();
        return $htmlOutput;
    }

    /**
     * Generate Html From Meta Tags
     *
     * @param array $tags
     */
    public function generateHtmlFromMetaTags(array $tags): string
    {
        $html = [];
        foreach ($tags as $tag => $value) {
            if (empty($value)) {
                continue;
            }
            $html[] = $this->createMetaTagHtml($tag, $value);
        }
        return implode('', $html);
    }

    /**
     * Create Meta Tag Html
     *
     * @param string $key
     * @param string $value
     */
    public function createMetaTagHtml(string $key, string $value): string
    {
        return sprintf(
            '<meta %s="%s%s" content="%s" />%s',
            $this->escaper->escapeHtml($this->metaAttribute, ENT_QUOTES, 'UTF-8'),
            $this->escaper->escapeHtml($this->tagPrefix, ENT_QUOTES, 'UTF-8'),
            $this->escaper->escapeHtml(strip_tags($key), ENT_QUOTES, 'UTF-8'),
            $this->escaper->escapeHtml(strip_tags($value), ENT_QUOTES, 'UTF-8'),
            PHP_EOL
        );
    }

    /**
     * Reset Meta Tags
     */
    public function resetMetaTags(): void
    {
        $this->metaTags = [];
        $this->tagPrefix = '';
        $this->metaAttribute = 'name';
    }
}
