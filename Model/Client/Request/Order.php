<?php
namespace Junipeer\ErpIntegration\Model\Client\Request;


class Order extends AbstractRequest
{

    public $orderId;

    public function buildDefaultRequest($orderId)
    {
        $this->orderId = $orderId;
    }

    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'order_id' =>  (int) $this->orderId,
        ];
    }


}
