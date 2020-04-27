<?php
namespace Junipeer\ErpIntegration\Model\Client\Request;


class Customer extends AbstractRequest
{

    public $customerId;

    public function buildDefaultRequest($customerId)
    {
        $this->customerId = $customerId;
    }

    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'customer_id' => (int) $this->customerId,
        ];
    }


}
