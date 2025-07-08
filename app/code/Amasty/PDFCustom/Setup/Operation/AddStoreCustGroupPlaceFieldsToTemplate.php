<?php

namespace Amasty\PDFCustom\Setup\Operation;

use Amasty\PDFCustom\Model\ResourceModel\Template as TemplateResource;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddStoreCustGroupPlaceFieldsToTemplate
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $this->addTemplateTypeField($setup);
        $this->addStoresField($setup);
        $this->addCustomerGroupField($setup);
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addTemplateTypeField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(TemplateResource::MAIN_TABLE),
            'place_for_use',
            [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Template Type'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addStoresField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(TemplateResource::MAIN_TABLE),
            'store_ids',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'default' => '',
                'comment' => 'Stores'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addCustomerGroupField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(TemplateResource::MAIN_TABLE),
            'customer_group_ids',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'default' => '',
                'comment' => 'Stores'
            ]
        );
    }
}
