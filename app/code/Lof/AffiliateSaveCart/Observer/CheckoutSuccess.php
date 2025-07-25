<?php
namespace Lof\AffiliateSaveCart\Observer;

use Magento\Framework\Event\ObserverInterface;

class CheckoutSuccess implements ObserverInterface
{
    protected $_quote;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $_collection;
    /**
     * @param Lof\Affiliate\Helper\Data
     */
    protected $_helper;

    protected $_accountAffiliate;

    /**
     * @var AffiliateSaveCartModelFactory
     */
    private $saveCartModelFactory;
    /**
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Sales\Model\ResourceModel\Order\Collection $collection,
        \Lof\Affiliate\Helper\Data $helper,
        \Lof\AffiliateSaveCart\Model\AffiliateSaveCartFactory $saveCartModelFactory,
        \Lof\Affiliate\Model\AccountAffiliateFactory $accountAffiliateFactory

    ){
        $this->_collection = $collection;
        $this->_helper = $helper;
        $this->_quote = $quote;
        $this->saveCartModelFactory = $saveCartModelFactory;
        $this->_accountAffiliate = $accountAffiliateFactory;
    }

    /**
     * Add New Layout handle
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->_helper->getConfig('general_settings/enable') || !$this->_helper->getConfig('affiliatesavecart/enable')) return;

        // check all order id and compare with conditions config
        $orderIds = $observer->getEvent()->getOrderIds();
        if (!$orderIds || !is_array($orderIds)) {
            return $this;
        }
        $this->_collection->addFieldToFilter('entity_id', ['in' => $orderIds]);
        $commission_action = $this->_helper->getConfig('affiliatesavecart/commission_action');
        $commission = $this->_helper->getConfig('affiliatesavecart/commission');
        
        foreach ($this->_collection as $order) {
            $order_customer_id = $order->getCustomerId();
            $order_customer_email = $order->getCustomerEmail();
            $order_id = $order->getEntityId();
            $orderSubTotal = $order->getSubtotal();
            $incrementId = $order->getIncrementId();
            $quote_id = $order->getQuoteId();
            $quote = $this->_quote->load($quote_id);
            $cart_id = $quote->getSavecartId();
            $saveCartModel = $this->saveCartModelFactory->create()->load($cart_id);
            $cart_commission = (float)$saveCartModel->getCommission();
            $quote_commission = 0;
            if ($commission_action == 1){
                $quote_commission += (float)$commission;
            } 
            else{
                $quote_commission += (float)$commission*(float)$orderSubTotal/100;
            } 

            if ($quote_commission <= 0) return;
            
            $customer_id = $saveCartModel->getCustomerId();
            if($customer_id == $order_customer_id) return; //return if same customer id
            $account_affiliate = $this->getAccountAffiliate($customer_id);
            if (!$account_affiliate->getId()) return;
            if($account_affiliate->getEmail() == $order_customer_email) return; //return if same customer email address
            $check = $this->checkValidQuote($quote->getAllItems(), $saveCartModel->getQuoteItems());
            if (!$check) return;
            $order->setAffiliateCode($account_affiliate->getTrackingCode())->save();
            $data['account_id'] = $account_affiliate->getId();
            $data['commission_total'] = $quote_commission;
            $data['description'] = __('Shared cart commision of order #') . $incrementId;
            $data['affiliate_code'] = $account_affiliate->getTrackingCode();
            $data['email_aff'] = $account_affiliate->getEmail();
            $data['campaign_code'] = '';
            $data['order_id'] = $order_id;
            $data['order_total'] = $orderSubTotal;
            $data['order_status'] = $order->getStatus();
            $data['transaction_stt'] = $order->getStatus();
            $data['increment_id'] = $incrementId;
            $data['base_currency_code'] = $order->getBaseCurrencyCode();
            $data['customer_email'] = $order->getCustomerEmail();
            $this->_helper->saveHistoryOrderAffiliate($data);
            $cart_commission += $quote_commission;
            $saveCartModel->setCommission($cart_commission)->save();
        }
    }

    public function getAccountAffiliate($customer_id)
    {
        $account_model = $this->_accountAffiliate->create()->loadByAttribute('customer_id', $customer_id);
        return $account_model;
    }

    public function checkValidQuote($quote_items, $cart_items)
    {
        $cart_items = unserialize($cart_items);
        foreach ($cart_items as $item) {
            $exist_item = false;
            $info = json_decode($item['info_buyRequest'], true);
            foreach ($quote_items as $quote_item) {
                if ($info['product'] == $quote_item->getProductId()) {
                    $exist_item = true;
                    if ($quote_item->getQty() < $item['qty']){
                        return false;
                        break;
                    }
                }
            }
            if (!$exist_item) {
                return false;
                break;
            }
        }
        return true;
    }
}
