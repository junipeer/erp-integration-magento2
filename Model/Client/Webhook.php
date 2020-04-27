<?php

namespace Junipeer\ErpIntegration\Model\Client;

class Webhook extends Client
{
    public function CreateCustomer(Request\Customer $request)
    {
        $endpoint = $this->buildEndpoint("/customer", []);
        try {
            $this->post($endpoint, $request);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }


    public function CreateOrder(Request\Order $request)
    {
        $endpoint = $this->buildEndpoint("/order", []);
        try {
            $this->post($endpoint, $request);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

}
