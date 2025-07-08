<?php

namespace Amasty\PDFCustom\Setup\Operation;

use Magento\Email\Model\TemplateFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddVariablesToDefaultTemplates
{
    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    public function __construct(TemplateFactory $templateFactory)
    {
        $this->templateFactory = $templateFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     *
     * @return void
     */
    public function execute(ModuleDataSetupInterface $setup)
    {
        $templatesArray = [
            'ampdf_creditmemo_template' => __('Credit memo default Template'),
            'ampdf_invoice_template'    => __('Invoice default Template'),
            'ampdf_order_template'      => __('Order default Template'),
            'ampdf_shipment_template'   => __('Shipment default Template')
        ];

        $connection = $setup->getConnection();
        $tableName = $setup->getTable('amasty_pdf_template');
        foreach ($templatesArray as $templateCode => $templateName) {
            $template = $this->templateFactory->create();
            $template->setForcedArea($templateCode);
            $template->loadDefault($templateCode);

            $bind = [
                'orig_template_code' => $templateCode,
                'orig_template_variables' => $template->getData('orig_template_variables'),
            ];

            $connection->update($tableName, $bind, ['template_code = ?' => $templateName]);
        }
    }
}
