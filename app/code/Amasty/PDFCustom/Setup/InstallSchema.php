<?php

namespace Amasty\PDFCustom\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Amasty\PDFCustom\Model\ResourceModel\Template as TemplateResource;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        /**
         * Create table 'amasty_pdf_template'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable(TemplateResource::MAIN_TABLE)
        )->addColumn(
            'template_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Template ID'
        )->addColumn(
            'template_code',
            Table::TYPE_TEXT,
            150,
            ['nullable' => false],
            'Template Name'
        )->addColumn(
            'template_text',
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => false],
            'Template Content'
        )->addColumn(
            'template_styles',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Template Styles'
        )->addColumn(
            'added_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Date of Template Creation'
        )->addColumn(
            'modified_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Date of Template Modification'
        )->addColumn(
            'orig_template_code',
            Table::TYPE_TEXT,
            200,
            [],
            'Original Template Code'
        )->addColumn(
            'orig_template_variables',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Original Template Variables'
        )->addIndex(
            $installer->getIdxName(
                TemplateResource::MAIN_TABLE,
                ['template_code'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['template_code'],
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        )->setComment(
            'PDF Templates by Amasty'
        );

        $installer->getConnection()->createTable($table);
    }
}
