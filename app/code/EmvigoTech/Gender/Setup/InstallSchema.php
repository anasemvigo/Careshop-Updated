<?php
namespace EmvigoTech\Gender\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $quote = $setup->getTable('quote');
        $salesOrder = $setup->getTable('sales_order');

        $setup->getConnection()->addColumn(
            $quote,
            'gender_field',
            [
                'type' => \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Gender'

            ]
            );

            $setup->getConnection()->addColumn(
                $salesOrder,
                'gender_field',
                [
                    'type' => \Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Gender'
    
                ]
                );

                $setup->endSetup();

    }
}
