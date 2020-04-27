<?php

namespace Junipeer\ErpIntegration\Model\Handler;

use Junipeer\ErpIntegration\Model\Client\Request\Order;
use Junipeer\ErpIntegration\Model\Client\Webhook;

class SendOrder
{

    protected $webhookApi;

    public function __construct(
        Webhook $webhookApi
    ) {
        $this->webhookApi = $webhookApi;
    }

    public function sendOrder($orderId)
    {
        $request = new Order();
        $request->orderId = $orderId;
        return $this->webhookApi->CreateOrder($request);
    }

}
