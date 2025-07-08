<?php

namespace Amasty\PDFCustom\Setup;

use Amasty\PDFCustom\Setup\Operation\AddStoreCustGroupPlaceFieldsToTemplate;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var AddStoreCustGroupPlaceFieldsToTemplate
     */
    private $addStoreCustGroupPlaceFieldsToTemplate;

    public function __construct(AddStoreCustGroupPlaceFieldsToTemplate $addStoreCustGroupPlaceFieldsToTemplate)
    {
        $this->addStoreCustGroupPlaceFieldsToTemplate = $addStoreCustGroupPlaceFieldsToTemplate;
    }

    /**
     * @inheritDoc
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $this->addStoreCustGroupPlaceFieldsToTemplate->execute($setup);
        }
        $setup->endSetup();
    }
}
