<?php

namespace Amasty\PDFCustom\Setup\Operation;

use Amasty\PDFCustom\Model\ComponentChecker;
use Amasty\PDFCustom\Model\ConfigProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class EnableDisableModuleAccordingToComponentExists
{
    /**
     * @var ComponentChecker
     */
    private $componentChecker;

    public function __construct(ComponentChecker $componentChecker)
    {
        $this->componentChecker = $componentChecker;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    public function execute(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $coreConfigDataTable = $setup->getTable('core_config_data');
        $select = $connection
            ->select()
            ->from(['t' => $coreConfigDataTable])
            ->where('path = ?', ConfigProvider::MODULE_SECTION . ConfigProvider::XPATH_ENABLED);

        $config = $connection->fetchRow($select);
        $isComponentsExist = $this->componentChecker->isComponentsExist();
        if (!empty($config['value']) && !$isComponentsExist) {
            $connection->update(
                $coreConfigDataTable,
                ['value' => '0'],
                ['config_id = ?' => $config['config_id']]
            );
        } elseif (empty($config) && $isComponentsExist) {
            $connection->insert(
                $coreConfigDataTable,
                [
                    'scope' => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                    'scope_id' => '0',
                    'path' => ConfigProvider::MODULE_SECTION . ConfigProvider::XPATH_ENABLED,
                    'value' => '1'
                ]
            );
        }
    }
}
