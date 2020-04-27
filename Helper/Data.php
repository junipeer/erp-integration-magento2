<?php
namespace Junipeer\ErpIntegration\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{


    const XML_PATH_CONNECTION     = 'junipeer_erpintegration/connection/';
    const XML_PATH_WEBHOOK     = 'junipeer_erpintegration/webhooks/';
    const API_BASE_URL_LIVE = "https://api.junipeer.io/";

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     *
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }


    public function getApiUsername($store = null) {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONNECTION.'public_api_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getApiPassword($store = null) {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONNECTION.'private_api_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getApiUrlPath($store = null) {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONNECTION.'api_url_path',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }


    public function isEnabled($store = null) {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CONNECTION.'enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }


    public function getApiUrl($store = null){
        return self::API_BASE_URL_LIVE;
    }

    public function sendOrders($store=null){
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_WEBHOOK.'send_orders',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );

    }

    public function sendCustomers($store=null){
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_WEBHOOK.'send_customers',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function sendCustomersOnSignup($store=null){
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_WEBHOOK.'send_customers_on_sign_up',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }


}
