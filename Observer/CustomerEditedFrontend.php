<?php
namespace Junipeer\ErpIntegration\Observer;


use Junipeer\ErpIntegration\Helper\Data;
use Junipeer\ErpIntegration\Model\Handler\SendCustomer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\ObserverInterface;

class CustomerEditedFrontend implements ObserverInterface
{

    protected $helper;
    protected $customerHandler;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /** @var CustomerRepositoryInterface $orderRepository */
    protected $customerRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        SendCustomer $customerHandler,
        Data $helper
    )
    {
        $this->customerHandler = $customerHandler;
        $this->helper = $helper;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // dont send if not enabled!
        if (!$this->helper->sendCustomers()) {
            return;
        }

        /** @var string $customerEmail */
        $customerEmail = $observer->getEmail();
        try {
            $store = $this->storeManager->getStore();
            $customer = $this->customerRepository->get($customerEmail, $store->getWebsiteId());
        } catch(\Exception $e) {
            // do nothing
            return;
        }

        try {
            $this->customerHandler->sendCustomer($customer->getId());
        } catch (\Exception $e) {
            // do nothing!
        }
    }

}
