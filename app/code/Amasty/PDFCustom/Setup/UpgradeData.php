<?php

namespace Amasty\PDFCustom\Setup;

use Amasty\PDFCustom\Setup\Operation\AddVariablesToDefaultTemplates;
use Amasty\PDFCustom\Setup\Operation\ChangeCssIncludeInTemplates;
use Amasty\PDFCustom\Setup\Operation\EnableDisableModuleAccordingToComponentExists;
use Amasty\PDFCustom\Setup\Operation\MigrateTemplatesFromConfigToTemplatesTable;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var MigrateTemplatesFromConfigToTemplatesTable
     */
    private $migrateTemplatesFromConfigToTemplatesTable;

    /**
     * @var ChangeCssIncludeInTemplates
     */
    private $changeCssIncludeInTemplates;

    /**
     * @var AddVariablesToDefaultTemplates
     */
    private $addVariablesToDefaultTemplates;

    /**
     * @var EnableDisableModuleAccordingToComponentExists
     */
    private $enableDisableModuleAccordingToComponentExists;

    public function __construct(
        MigrateTemplatesFromConfigToTemplatesTable $migrateTemplatesFromConfigToTemplatesTable,
        ChangeCssIncludeInTemplates $changeCssIncludeInTemplates,
        AddVariablesToDefaultTemplates $addVariablesToDefaultTemplates,
        EnableDisableModuleAccordingToComponentExists $enableDisableModuleAccordingToComponentExists
    ) {
        $this->migrateTemplatesFromConfigToTemplatesTable = $migrateTemplatesFromConfigToTemplatesTable;
        $this->changeCssIncludeInTemplates = $changeCssIncludeInTemplates;
        $this->addVariablesToDefaultTemplates = $addVariablesToDefaultTemplates;
        $this->enableDisableModuleAccordingToComponentExists = $enableDisableModuleAccordingToComponentExists;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $this->addVariablesToDefaultTemplates->execute($setup);
            $this->migrateTemplatesFromConfigToTemplatesTable->execute($setup);
            $this->changeCssIncludeInTemplates->execute($setup);
            $this->enableDisableModuleAccordingToComponentExists->execute($setup);
        }

        $setup->endSetup();
    }
}
