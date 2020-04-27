<?php
namespace Junipeer\ErpIntegration\Observer;


use Junipeer\ErpIntegration\Helper\Data;
use Junipeer\ErpIntegration\Model\Handler\SendCustomer;
use Magento\Customer\Model\Customer;
use Magento\Framework\Event\ObserverInterface;

class CustomerCreatedInAdmin implements ObserverInterface
{

    protected $helper;
    protected $customerHandler;

    public function __construct(
        SendCustomer $customerHandler,
        Data $helper
    )
    {
        $this->customerHandler = $customerHandler;
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // dont send if not enabled!
        if (!$this->helper->sendCustomers()) {
            return;
        }

        /** @var Customer $customer */
        $customer = $observer->getCustomer();
        try {
            $this->customerHandler->sendCustomer($customer->getId());
        } catch (\Exception $e) {
            // do nothing!
        }
    }

}
