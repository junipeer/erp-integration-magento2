<?php


namespace Junipeer\ErpIntegration\Model\Handler;


use Junipeer\ErpIntegration\Model\Client\Request\Customer;
use Junipeer\ErpIntegration\Model\Client\Webhook;

class SendCustomer
{

    /**
     * @var $webhookApi Webhook
     */
    protected $webhookApi;

    public function __construct(
        Webhook $webhookApi
    ) {
        $this->webhookApi = $webhookApi;
    }

    public function sendCustomer($customerId)
    {
        $request = new Customer();
        $request->customerId = $customerId;
        return $this->webhookApi->CreateCustomer($request);
    }

}
