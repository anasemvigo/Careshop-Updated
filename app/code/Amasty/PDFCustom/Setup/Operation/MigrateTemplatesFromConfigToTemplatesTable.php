<?php

namespace Amasty\PDFCustom\Setup\Operation;

use Amasty\PDFCustom\Model\ConfigProvider;
use Amasty\PDFCustom\Model\ResourceModel\Template as TemplateResource;
use Amasty\PDFCustom\Model\Source\PlaceForUse;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\ScopeInterface;

class MigrateTemplatesFromConfigToTemplatesTable
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    public function execute(ModuleDataSetupInterface $setup)
    {
        $this->migrateConfigTemplates($setup);
        $this->updatePlaceForUseForOtherTemplates($setup);
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function migrateConfigTemplates(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $types = [
            PlaceForUse::TYPE_ORDER => 'order',
            PlaceForUse::TYPE_INVOICE => 'invoice',
            PlaceForUse::TYPE_SHIPPING => 'shipment',
            PlaceForUse::TYPE_CREDIT_MEMO => 'creditmemo',
        ];
        $templateToType = [];
        foreach ($types as $typeId => $typeName) {
            $templateToStore = $this->getTemplatesWithStoresByType(
                $setup,
                $typeName
            );
            foreach ($templateToStore as $templateId => $storeIds) {
                $storeIdsString = implode(',', $storeIds);
                if (array_key_exists($templateId, $templateToType)) {
                    $postfix = $this->getNewTemplateCodePostfix($setup, $typeName, $templateId);
                    $select = $connection->select()
                        ->from(
                            $setup->getTable(TemplateResource::MAIN_TABLE),
                            [
                                new \Zend_Db_Expr('CONCAT(template_code, " - ' . $postfix . '")'),
                                'template_text',
                                'template_styles',
                                'orig_template_code',
                                'orig_template_variables',
                                new \Zend_Db_Expr("'{$typeId}'"),
                                new \Zend_Db_Expr("'{$storeIdsString}'"),
                            ]
                        )->where('template_id = ?', $templateId);

                    $connection->query(
                        $connection->insertFromSelect(
                            $select,
                            $setup->getTable(TemplateResource::MAIN_TABLE),
                            [
                                'template_code',
                                'template_text',
                                'template_styles',
                                'orig_template_code',
                                'orig_template_variables',
                                'place_for_use',
                                'store_ids',
                            ]
                        )
                    );
                    continue;
                }

                $connection->update(
                    $setup->getTable(TemplateResource::MAIN_TABLE),
                    [
                        'store_ids' => $storeIdsString,
                        'place_for_use' => $typeId
                    ],
                    ['template_id = ?' => $templateId]
                );
                $templateToType[$templateId] = $typeId;
            }

        }
    }

    /**
     * Getting list of templates and store ids for that templates
     *
     * @param ModuleDataSetupInterface $setup
     * @param string $typeCode config code
     *
     * @return array
     */
    private function getTemplatesWithStoresByType(
        ModuleDataSetupInterface $setup,
        $typeCode
    ) {
        $websites = $this->storeManager->getWebsites();
        $allStores = array_keys($this->storeManager->getStores(true));
        $connection = $setup->getConnection();
        $select = $connection
            ->select()
            ->from(['t' => $setup->getTable('core_config_data')])
            ->where('path = ?', ConfigProvider::MODULE_SECTION . $typeCode . '/template')
            ->where('value != 0');

        $configs = $connection->fetchAll($select);

        $templateToStore = [];
        $usedStoreIds = [];

        foreach ($configs as $config) {
            if ($config['scope'] != ScopeInterface::SCOPE_STORES) {
                continue;
            }
            $templateId = $config['value'];
            $storeId = $config['scope_id'];
            $templateToStore[$templateId][$storeId] = $storeId;
            $usedStoreIds[$storeId] = $storeId;
        }

        foreach ($configs as $config) {
            if ($config['scope'] != ScopeInterface::SCOPE_WEBSITES || empty($websites[$config['scope_id']])) {
                continue;
            }
            $templateId = $config['value'];
            $website = $websites[$config['scope_id']];
            $storeIds = $website->getStoreIds();
            $storeIds = array_diff($storeIds, $usedStoreIds);
            foreach ($storeIds as $storeId) {
                $templateToStore[$templateId][$storeId] = $storeId;
                $usedStoreIds[$storeId] = $storeId;
            }
        }

        $storeIds = $allStores;
        $storeIds = array_diff($storeIds, $usedStoreIds);
        foreach ($configs as $config) {
            if ($config['scope'] != ScopeConfigInterface::SCOPE_TYPE_DEFAULT) {
                continue;
            }
            $templateId = $config['value'];
            foreach ($storeIds as $storeId) {
                $templateToStore[$templateId][$storeId] = $storeId;
            }
        }

        return $templateToStore;
    }

    /**
     * Getting new template code for clones
     * @param ModuleDataSetupInterface $setup
     * @param string $typeName
     * @param int $templateId
     * @return string
     */
    private function getNewTemplateCodePostfix(ModuleDataSetupInterface $setup, $typeName, $templateId)
    {
        $select = $setup->getConnection()->select()
            ->from(
                $setup->getTable(TemplateResource::MAIN_TABLE),
                ['template_code']
            )->where(
                'template_id = ?',
                $templateId
            );
        $templateName = $setup->getConnection()->fetchOne($select);

        $counter = 1;
        $postfix = '';
        do {
            if ($counter > 1) {
                $postfix = ' ' . $counter;
            }

            $select = $setup->getConnection()->select()
                ->from(
                    $setup->getTable(TemplateResource::MAIN_TABLE),
                    [new \Zend_Db_Expr('COUNT(*)')]
                )->where(
                    'template_code = ?',
                    $templateName . ' - ' . $typeName . $postfix
                );

            $countRows = $setup->getConnection()->fetchOne($select);
            $counter++;
        } while ($countRows);

        return $typeName . $postfix;
    }

    /**
     * Update place_for_use for unused templates according to orig_template_code
     *
     * @param ModuleDataSetupInterface $setup
     */
    private function updatePlaceForUseForOtherTemplates(ModuleDataSetupInterface $setup)
    {
        $templatesArray = [
            'ampdf_creditmemo_template' => PlaceForUse::TYPE_CREDIT_MEMO,
            'ampdf_invoice_template'    => PlaceForUse::TYPE_INVOICE,
            'ampdf_order_template'      => PlaceForUse::TYPE_ORDER,
            'ampdf_shipment_template'   => PlaceForUse::TYPE_SHIPPING,
        ];
        $connection = $setup->getConnection();
        $tableName = $setup->getTable(TemplateResource::MAIN_TABLE);

        foreach ($templatesArray as $templateCode => $placeForUse) {
            $connection->update(
                $tableName,
                [
                    'place_for_use' => $placeForUse
                ],
                [
                    'orig_template_code = ?' => $templateCode,
                    'place_for_use = 0'
                ]
            );
        }
    }
}
