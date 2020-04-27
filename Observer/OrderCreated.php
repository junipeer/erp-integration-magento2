<?php
namespace Junipeer\ErpIntegration\Observer;

use Junipeer\ErpIntegration\Helper\Data;
use Junipeer\ErpIntegration\Model\Handler\SendOrder;
use Magento\Framework\Event\ObserverInterface;

class OrderCreated implements ObserverInterface
{

    protected $helper;
    protected $orderHandler;

    public function __construct(
        SendOrder $sendOrder,
        Data $helper
    )
    {
        $this->orderHandler = $sendOrder;
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // dont send if not enabled!
        if (!$this->helper->sendOrders()) {
            return;
        }

        /** @var array $orderIds */
        $orderIds = $observer->getOrderIds();
        try {
            if (isset($orderIds[0])) {
                $this->orderHandler->sendOrder($orderIds[0]);
            }
        } catch (\Exception $e) {
            // do nothing!
        }
    }

}
