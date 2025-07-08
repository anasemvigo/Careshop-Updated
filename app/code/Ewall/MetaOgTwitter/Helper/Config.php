<?php
/**
 * @copyright: Copyright © 2024 Ewall, Inc. All rights reserved.
 * @See COPYING.txt for license details.
 * @author   : Ewall
 * @keywords : Module Ewall_MetaOgTwitter
 */
declare(strict_types=1);

namespace Ewall\MetaOgTwitter\Helper;

use Magento\Store\Model\ScopeInterface;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * CMS Twitter Tags Enable
     */
    protected const CMS_TWITTER_TAGS_ENABLE = 'seo_tags/twitter_tags/tags_cms/enable_twitter_cms';
   
    /**
     * Cms Twitter Title
     */
    protected const CMS_TWITTER_TITLE = 'seo_tags/twitter_tags/tags_cms/twitter_title_cms';

    /**
     * Cms Twitter Description
     */
    protected const CMS_TWITTER_DESCRIPTION = 'seo_tags/twitter_tags/tags_cms/twitter_description_cms';

    /**
     * Cms Og Tags Enable
     */
    protected const CMS_OG_TAGS_ENABLE = 'seo_tags/og_tags/tags_cms/enable_og_cms';
   
    /**
     * Cms Og Title
     */
    protected const CMS_OG_TITLE = 'seo_tags/og_tags/tags_cms/og_title_cms';

    /**
     * Cms Og Description
     */
    protected const CMS_OG_DESCRIPTION = 'seo_tags/og_tags/tags_cms/og_description_cms';

    /**
     * Category Twitter Tags Enable
     */
    protected const CATEGORY_TWITTER_TAGS_ENABLE = 'seo_tags/twitter_tags/tags_category/enable_twitter_category';
   
    /**
     * Category Twitter Tags Title
     */
    protected const CATEGORY_TWITTER_TITLE = 'seo_tags/twitter_tags/tags_category/twitter_title_category';

    /**
     * Category Twitter Tags Description
     */
    protected const CATEGORY_TWITTER_DESCRIPTION = 'seo_tags/twitter_tags/tags_category/twitter_description_category';

    /**
     * Category Og Tags Enable
     */
    protected const CATEGORY_OG_TAGS_ENABLE = 'seo_tags/og_tags/tags_category/enable_og_category';
   
    /**
     *  Category Og Title
     */
    protected const CATEGORY_OG_TITLE = 'seo_tags/og_tags/tags_category/og_title_category';

    /**
     * Category Og Description
     */
    protected const CATEGORY_OG_DESCRIPTION = 'seo_tags/og_tags/tags_category/og_description_category';

    /**
     * Product Twitter Tags Enable
     */
    protected const PRODUCT_TWITTER_TAGS_ENABLE = 'seo_tags/twitter_tags/tags_product/enable_twitter_product';
   
    /**
     * Product Twitter Title
     */
    protected const PRODUCT_TWITTER_TITLE = 'seo_tags/twitter_tags/tags_product/twitter_title_product';

    /**
     * Product Twitter Description
     */
    protected const PRODUCT_TWITTER_DESCRIPTION = 'seo_tags/twitter_tags/tags_product/twitter_description_product';

    /**
     * Enable Og Product Tags
     */
    protected const PRODUCT_OG_TAGS_ENABLE = 'seo_tags/og_tags/tags_product/enable_og_product';
   
    /**
     * Product Og Title
     */
    protected const PRODUCT_OG_TITLE = 'seo_tags/og_tags/tags_product/og_title_product';

    /**
     * Product Og Description
     */
    protected const PRODUCT_OG_DESCRIPTION = 'seo_tags/og_tags/tags_product/og_description_product';

    /**
     * Twitter Card Type
     */
    protected const TWITTER_CARD_TYPE = 'seo_tags/twitter_tags/twitter_card_type';
   
    /**
     *  Twitter Username
     */
    protected const TWITTER_USERNAME = 'seo_tags/twitter_tags/twitter_username';

    /**
     * Twitter Author
     */
    protected const TWITTER_AUTHOR = 'seo_tags/twitter_tags/twitter_author';

    /**
     * IPhone App Id
     */
    protected const IPHONE_APP_ID = 'seo_tags/twitter_tags/app_intergration/iphone_app_id';

    /**
     * IPhone App Name
     */
    protected const IPHONE_APP_NAME = 'seo_tags/twitter_tags/app_intergration/iphone_app_name';

    /**
     * Iphone App Url
     */
    protected const IPHONE_APP_URL = 'seo_tags/twitter_tags/app_intergration/iphone_app_url';

    /**
     * IPAD APP ID
     */
    protected const IPAD_APP_ID = 'seo_tags/twitter_tags/app_intergration/ipad_app_id';

    /**
     * Ipad APP Name
     */
    protected const IPAD_APP_NAME = 'seo_tags/twitter_tags/app_intergration/ipad_app_name';

    /**
     * Ipad App Url
     */
    protected const IPAD_APP_URL = 'seo_tags/twitter_tags/app_intergration/ipad_app_url';

    /**
     * Android App Id
     */
    protected const ANDROID_APP_ID = 'seo_tags/twitter_tags/app_intergration/android_app_id';

    /**
     * Android App Name
     */
    protected const ANDROID_APP_NAME = 'seo_tags/twitter_tags/app_intergration/android_app_name';

    /**
     * Android App Url
     */
    protected const ANDROID_APP_URL = 'seo_tags/twitter_tags/app_intergration/android_app_url';

    /**
     * Player Url
     */
    protected const PLAYER_URL = 'seo_tags/twitter_tags/player_intergration/player_url';

    /**
     * Player Width
     */
    protected const PLAYER_WIDTH = 'seo_tags/twitter_tags/player_intergration/player_width';

    /**
     * Player Height
     */
    protected const PLAYER_HEIGHT = 'seo_tags/twitter_tags/player_intergration/player_height';

   /**
    * Is Enable Cms Twitter Tags
    */
    public function isEnableCMSTwitterTag()
    {
        return $this->scopeConfig->getValue(self::CMS_TWITTER_TAGS_ENABLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Ser Cms Twitter Title
    */
    public function isSetCMSTwitterTitle()
    {
        return $this->scopeConfig->getValue(self::CMS_TWITTER_TITLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Cms Twitter Description
    */
    public function isSetCMSTwitterDescription()
    {
        return $this->scopeConfig->getValue(self::CMS_TWITTER_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Cms og Tags
    */
    public function isEnableCMSOgTag()
    {
        return $this->scopeConfig->getValue(self::CMS_OG_TAGS_ENABLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Cms Og Title
    */
    public function isSetCMSOgTitle()
    {
        return $this->scopeConfig->getValue(self::CMS_OG_TITLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Cms Og Description
    */
    public function isSetCMSOgDescription()
    {
        return $this->scopeConfig->getValue(self::CMS_OG_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Category Twitter Tag
    */
    public function isEnableCategoryTwitterTag()
    {
        return $this->scopeConfig->getValue(self::CATEGORY_TWITTER_TAGS_ENABLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Category Twitter Title
    */
    public function isSetCategoryTwitterTitle()
    {
        return $this->scopeConfig->getValue(self::CATEGORY_TWITTER_TITLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Category Twitter Description
    */
    public function isSetCategoryTwitterDescription()
    {
        return $this->scopeConfig->getValue(self::CATEGORY_TWITTER_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Catgory Og Tag
    */
    public function isEnableCategoryOgTag()
    {
        return $this->scopeConfig->getValue(self::CATEGORY_OG_TAGS_ENABLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Category Og Title
    */
    public function isSetCategoryOgTitle()
    {
        return $this->scopeConfig->getValue(self::CATEGORY_OG_TITLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Category Og Description
    */
    public function isSetCategoryOgDescription()
    {
        return $this->scopeConfig->getValue(self::CATEGORY_OG_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Product Twitter Tag
    */
    public function isEnableProductTwitterTag()
    {
        return $this->scopeConfig->getValue(self::PRODUCT_TWITTER_TAGS_ENABLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Product Twitter Title
    */
    public function isSetProductTwitterTitle()
    {
        return $this->scopeConfig->getValue(self::PRODUCT_TWITTER_TITLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Product Twitter Description
    */
    public function isSetProductTwitterDescription()
    {
        return $this->scopeConfig->getValue(self::PRODUCT_TWITTER_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Product Og Tag
    */
    public function isEnableProductOgTag()
    {
        return $this->scopeConfig->getValue(self::PRODUCT_OG_TAGS_ENABLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Product Og Title
    */
    public function isSetProductOgTitle()
    {
        return $this->scopeConfig->getValue(self::PRODUCT_OG_TITLE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Product Og Description
    */
    public function isSetProductOgDescription()
    {
        return $this->scopeConfig->getValue(self::PRODUCT_OG_DESCRIPTION, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Set Twitter Card Type
    */
    public function isSetTwitterCardType()
    {
        return $this->scopeConfig->getValue(self::TWITTER_CARD_TYPE, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Twitter Username
    */
    public function getTwitterUsername()
    {
        return $this->scopeConfig->getValue(self::TWITTER_USERNAME, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Twitter Author
    */
    public function getTwitterAuthor()
    {
        return $this->scopeConfig->getValue(self::TWITTER_AUTHOR, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Cms Twitter Tags
    */
    public function getIphoneAppId()
    {
        return $this->scopeConfig->getValue(self::IPHONE_APP_ID, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Cms Twitter Tags
    */
    public function getIphoneAppName()
    {
        return $this->scopeConfig->getValue(self::IPHONE_APP_NAME, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Cms Twitter Tags
    */
    public function getIphoneAppUrl()
    {
        return $this->scopeConfig->getValue(self::IPHONE_APP_URL, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Cms Twitter Tags
    */
    public function getIpadAppId()
    {
        return $this->scopeConfig->getValue(self::IPAD_APP_ID, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Cms Twitter Tags
    */
    public function getIpadAppName()
    {
        return $this->scopeConfig->getValue(self::IPAD_APP_NAME, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Is Enable Cms Twitter Tags
    */
    public function getIpadAppUrl()
    {
        return $this->scopeConfig->getValue(self::IPAD_APP_URL, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Android App Id
    */
    public function getAndroidAppId()
    {
        return $this->scopeConfig->getValue(self::ANDROID_APP_ID, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Android App Id
    */
    public function getAndroidAppName()
    {
        return $this->scopeConfig->getValue(self::ANDROID_APP_NAME, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Android App Id
    */
    public function getAndroidAppUrl()
    {
        return $this->scopeConfig->getValue(self::ANDROID_APP_URL, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Android App Id
    */
    public function getPayerUrl()
    {
        return $this->scopeConfig->getValue(self::PLAYER_URL, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Player Width
    */
    public function getPayerWidth()
    {
        return $this->scopeConfig->getValue(self::PLAYER_WIDTH, ScopeInterface::SCOPE_STORE);
    }

   /**
    * Get Player Height
    */
    public function getPayerHeight()
    {
        return $this->scopeConfig->getValue(self::PLAYER_HEIGHT, ScopeInterface::SCOPE_STORE);
    }

}