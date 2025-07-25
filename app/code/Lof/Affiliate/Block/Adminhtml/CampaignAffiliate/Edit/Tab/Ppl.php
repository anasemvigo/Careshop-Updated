<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the venustheme.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_Affiliate
 * @copyright  Copyright (c) 2016 Landofcoder (https://landofcoder.com)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\Affiliate\Block\Adminhtml\CampaignAffiliate\Edit\Tab;

class Ppl extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Lof\Affiliate\Model\ResourceModel\Affiliate\Collection
     */
    protected $_campaignCollection;

    /**
     * @param \Magento\Backend\Block\Template\Context
     * @param \Magento\Framework\Registry
     * @param \Magento\Framework\Data\FormFactory
     * @param \Magento\Store\Model\System\Store
     * @param \Magento\Cms\Model\Wysiwyg\Config
     * @param array
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Lof\Affiliate\Model\ResourceModel\CampaignAffiliate\Collection $campaignCollection,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_campaignCollection = $campaignCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('affiliate_campaign');
        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Lof_Affiliate::campaign_edit') || $this->_isAllowedAction('Lof_Affiliate::campaign_new')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
        $this->_eventManager->dispatch(
            'lof_check_license',
            ['obj' => $this,'ex'=>'Lof_Affiliate']
            );

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('campaignaffiliate_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Commission - Pay Per Lead')]);

        if ($model->getId()) {
            $fieldset->addField('campaign_id', 'hidden', ['name' => 'campaign_id']);
        }


        $fieldset->addField(
            'enable_ppl',
            'select',
            [
                'name' => 'enable_ppl',
                'label' => __('Enable Pay Per Lead  '),
                'title' => __('Enable Pay Per Lead  '),
                'required' => false,
                'disabled' => $isElementDisabled,
                'options' => $model->getYesNoField(),
            ]
        );
        $fieldset->addField(
            'signup_commission',
            'text',
            [
                'name' => 'signup_commission',
                'label' => __('Commission for each signup new account ($)'),
                'title' => __('Commission for each signup new account ($)'),
                'class' => 'validate-number',
                'required' => false,
                'disabled' => $isElementDisabled,
            ]
        );
        $fieldset->addField(
            'limit_account',
            'text',
            [
                'name' => 'limit_account',
                'label' => __('Maximum quantity of signup new account for each Affiliater'),
                'title' => __('Maximum quantity of signup new account for each Affiliater'),
                'class' => 'validate-digits',
                'required' => false,
                'disabled' => $isElementDisabled,
            ]
        );
        $fieldset->addField(
            'limit_balance',
            'text',
            [
                'name' => 'limit_balance',
                'label' => __('Maximum pay per lead balance for each Affiliater'),
                'title' => __('Maximum pay per lead balance for each Affiliater'),
                'class' => 'validate-number',
                'required' => false,
                'disabled' => $isElementDisabled,
            ]
        );
        $fieldset->addField(
            'limit_account_ip',
            'text',
            [
                'name' => 'limit_account_ip',
                'label' => __('Maximum new account created from same IP address'),
                'title' => __('Maximum new account created from same IP address'),
                'class' => 'validate-digits',
                'required' => false,
                'disabled' => $isElementDisabled,
            ]
        );

        $this->_eventManager->dispatch('adminhtml_affiliate_campaignaffiliate_edit_tab_detail_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getCampaignCollection()
    {
        $model = $this->_coreRegistry->registry('affiliate_campaign');
        $collection = $this->_campaignCollection
            ->addFieldToFilter('campaign_id', array('neq' => $model->getId()));
        return $collection;
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Campaign Affiliate Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Campaign Affiliate Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
