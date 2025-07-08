<?php
namespace Rudracomputech\Ogtags\Ui;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResultFactory;

class DataProvider extends AbstractDataProvider
{
    protected $searchResultFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        SearchResultFactory $searchResultFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->searchResultFactory = $searchResultFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $collection = $this->searchResultFactory->create();
        return ['items' => $collection->getItems(), 'totalRecords' => $collection->getSize()];
    }
}
