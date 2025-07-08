<?php
namespace Rudracomputech\Ogtags\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{
    protected $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        // Add product attributes
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'og_title',
            [
                'type' => 'varchar',
                'label' => 'OG Title',
                'input' => 'text',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'user_defined' => true,
                'group' => 'Search Engine Optimization'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'og_image',
            [
                'type' => 'varchar',
                'label' => 'OG Image',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Product\Attribute\Backend\Media',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'user_defined' => true,
                'group' => 'Search Engine Optimization'
            ]
        );

        // Add category attributes
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'og_title',
            [
                'type' => 'varchar',
                'label' => 'OG Title',
                'input' => 'text',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'group' => 'Search Engine Optimization'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'og_image',
            [
                'type' => 'varchar',
                'label' => 'OG Image',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Media',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'group' => 'Search Engine Optimization'
            ]
        );

        $setup->endSetup();
    }
}
