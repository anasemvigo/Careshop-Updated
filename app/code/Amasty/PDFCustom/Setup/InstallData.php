<?php

namespace Amasty\PDFCustom\Setup;

use Exception;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    private $resourceConfig;

    public function __construct(ConfigInterface $resourceConfig)
    {
        $this->resourceConfig = $resourceConfig;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @throws Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $templatesDefaultData = $this->createPdfTemplatesData();
        foreach ($templatesDefaultData as $bind) {
            $config_code = $bind['template_code_config'];
            unset($bind['template_code_config']);
            $connection = $setup->getConnection();
            $connection->insert($setup->getTable('amasty_pdf_template'), $bind);
            $this->resourceConfig->saveConfig(
                $config_code,
                $connection->lastInsertId('amasty_pdf_template'),
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
        }
        $setup->endSetup();
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function createPdfTemplatesData()
    {
        $templatesArray =
            [
                'ampdf_creditmemo_template' => __('Credit memo default Template'),
                'ampdf_invoice_template'    => __('Invoice default Template'),
                'ampdf_order_template'      => __('Order default Template'),
                'ampdf_shipment_template'   => __('Shipment default Template')
            ];
        $templatesDefaultData = [];
        foreach ($templatesArray as $templateCode => $templateName) {

            $template = ObjectManager::getInstance()
                ->create('Magento\Email\Model\Template');

            $template->setForcedArea($templateCode);

            $template->loadDefault($templateCode);
            $templateText = $template->getTemplateText();
            $templateStyles = $template->getTemplateStyles();
            $templatesDefaultData[] = [
                'template_code'        => $templateName,
                'template_text'        => $templateText,
                'template_styles'      => $templateStyles,
                'template_code_config' => str_replace('_', '/', $templateCode)
            ];
        }

        return $templatesDefaultData;
    }
}
