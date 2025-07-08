<?php
namespace Magento\Layout\Observer;
use Magento\Framework\Event\ObserverInterface;

class ForceStorecodeRedirectObserver implements ObserverInterface
{
    protected $storeManager;
    protected $url;
    /** @var string $defaultStorecode */
    protected $defaultStorecode = 'en';
    /** @var array $storeCodes - array of existing storecodes*/
    protected $storeCodes = [1=>'en',3=>'fr',4=>'de',5=>'it'];

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $url
    ) {
       $this->storeManager = $storeManager;
       $this->url = $url;
      // $this->storeCodes = array_keys($this->storeManager->getStores(false, true));
      //  $this->defaultStorecode = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
         $this->defaultStorecode = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) 
      ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) 
      : '';
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
         $urlParts = parse_url($this->url->getCurrentUrl());
         $path = $urlParts['path'];

         $urlCode = trim(substr($path, 0, 4), '/');

         // get storecode from URL
      //   $urlCode = trim(substr($path, 0, 4), '/');



  

         // If path does not already contain an existing storecode
         if (!in_array($urlCode, $this->storeCodes) && strpos($this->url->getCurrentUrl(), "static") === false  && strpos($this->url->getCurrentUrl(), "brandimage") === false ) {
             $path = ltrim($path, '/');

            $keyid = array_search ($this->defaultStorecode, $this->storeCodes);
            


            if(empty($keyid)){
            $keyid = 1;
            }
               $this->storeManager->setCurrentStore($keyid);


               // Redirect to URL including storecode
                            header("HTTP/1.1 301 Moved Permanently");
                            header("Location: " . $this->storeManager->getStore()->getBaseUrl().$path);
                            exit();

       }
    }
}